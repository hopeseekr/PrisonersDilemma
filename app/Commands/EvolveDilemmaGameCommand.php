<?php declare(strict_types=1);

/**
 * This file is part of The Prisoner's Dilemma Project, by Theodore R. Smith.
 *
 * Copyright © 2020 Theodore R. Smith <theodore@phpexperts.pro>.
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *   https://github.com/hopeseekr/PrisonersDilemma
 *
 * This file is licensed under the MIT License.
 */

namespace App\Commands;

use HopeSeekr\PrisonersDilemma\IncomeCalculator;
use HopeSeekr\PrisonersDilemma\People\PeopleFitnessSolution;
use HopeSeekr\PrisonersDilemma\People\PersonFitnessScore;
use HopeSeekr\PrisonersDilemma\People\Suspect;
use HopeSeekr\PrisonersDilemma\People\SuspectDecision;
use HopeSeekr\PrisonersDilemma\People\SuspectGenome;
use HopeSeekr\PrisonersDilemma\State\Adjudicator;
use HopeSeekr\PrisonersDilemma\State\CriminalCode;
use HopeSeekr\PrisonersDilemma\State\Interrogator;
use HopeSeekr\PrisonersDilemma\State\PrisonSentence;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use PHPExperts\ConsolePainter\ConsolePainter;
use PHPExperts\DataTypeValidator\InvalidDataTypeException;
use PHPExperts\GAO\Breeder;
use PHPExperts\GAO\Population;

class EvolveDilemmaGameCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'evolve:classic';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Evolves the Prisoner\'s Dilemma thought experiment.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Let's start everyone off as Homo Sapien Sapiens who know nothing and behave quite naively.
        $protagonistGenome = new SuspectGenome();
        $antagonistGenome = new SuspectGenome();

        for ($generation = 1; $generation <= 500; ++$generation) {
            /** @var PersonFitnessScore $yourFitnessDTO */
            /** @var PersonFitnessScore $theirFitnessDTO */
            [$convictions, $yourFitnessDTO, $theirFitnessDTO] = $this->runRound($protagonistGenome, $antagonistGenome);

            $myFitness = $yourFitnessDTO->getFitnessScore();
            $theirFitness = $theirFitnessDTO->getFitnessScore();

            $cp = new ConsolePainter();
            $this->line($cp->onRed()->white()->bold("  === Generation $generation ===  "));
            $this->line($cp->onDarkGray()->white()->bold("  Protagonist Fitness: $myFitness\n" . json_encode($protagonistGenome, JSON_PRETTY_PRINT)));
            $this->line($cp->onDarkGray()->red()->bold("  Antagonist Fitness: $theirFitness\n" . json_encode($antagonistGenome, JSON_PRETTY_PRINT)));

            usleep(222222);
        }
    }

    private function runRound(SuspectGenome $protagonistGenome, SuspectGenome $antagonistGenome)
    {
        $hourlyWage = 10;

        $cp = new ConsolePainter();
        $play = function (CriminalCode $criminalLaw) use ($hourlyWage, $protagonistGenome, $antagonistGenome): array {
            $interrogator = new Interrogator();
            $protagonist = new Suspect($protagonistGenome);
            $partner = new Suspect($antagonistGenome);

            $protagonistChoice = $interrogator->interrogate($protagonist);
            $partnerChoice = $interrogator->interrogate($partner);
            $partnerDecision = SuspectDecision::getThirdPartyResponse($partnerChoice);
            dump($partnerChoice);

            $this->line((new ConsolePainter())->onRed()->white()->bold("   Your partner's decision: $partnerDecision.   "));

            $judge = new Adjudicator($criminalLaw);

            $convictions = $judge->issueSentence($protagonistChoice, $partnerChoice);

            $maxPossibleSentence = $criminalLaw->getMaxSentence();

            $yourIncome = IncomeCalculator::calculate($hourlyWage, $maxPossibleSentence - $convictions['you']);
            $antagonistIncome = IncomeCalculator::calculate($hourlyWage, $maxPossibleSentence - $convictions['partner']);

            // @todo: It feels *so* dirty to hard-code this like this :-/
            $yourFitnessDTO = new PersonFitnessScore([
                'individualWages' => $yourIncome,
                'communityWages'  => $yourIncome + $antagonistIncome,
                'selfishWeight'   => $protagonistGenome->selfishWeight,
            ]);

            try {
                $antagonistFitnessDTO = new PersonFitnessScore([
                    'individualWages' => $yourIncome,
                    'communityWages'  => $yourIncome + $antagonistIncome,
                    'selfishWeight'   => $antagonistGenome->selfishWeight,
                ]);
            } catch (InvalidDataTypeException $e) {
                dd($e->getReasons());
            }

            return [
                $convictions,
                $yourFitnessDTO,
                $antagonistFitnessDTO,
            ];
        };

        $criminalLaw = new PrisonSentence();
        /** @var PersonFitnessScore $yourFitnessDTO */
        /** @var PersonFitnessScore $antagonistFitnessDTO */
        $roundData = $play($criminalLaw);
        [$convictions, $yourFitnessDTO, $antagonistFitnessDTO] = $roundData;
        dump($convictions);

        $this->line($cp->onBlack()->bold()->yellow('   Minimum Wage: ')->red("\$$hourlyWage"));

        $years = $convictions['you'] === 1 ? 'year' : 'years';
        $yourConviction = $convictions['you'] === 0 ? 'acquitted and are now free!' : "convicted for $convictions[you] $years.";
        $years = $convictions['partner'] === 1 ? 'year' : 'years';
        $partnerConviction = $convictions['partner'] === 0 ? 'acquitted and is now free!' : "convicted for $convictions[partner] $years.";

        $yourIncome = '$' . number_format($yourFitnessDTO->individualWages);
        $partnerIncome = '$' . number_format($antagonistFitnessDTO->individualWages);
        $totalIncome = '$' . number_format($antagonistFitnessDTO->communityWages);

        $outcome = $cp->onBlue()->bold();
        $this->line($outcome(str_pad('   Outcomes:', 72)));
        $this->line($outcome(str_pad("      - You were $yourConviction", 55) . str_pad("Income: $yourIncome", 17)));
        $this->line($outcome(str_pad("      - Your partner was $partnerConviction", 55) . str_pad("Income: $partnerIncome", 17)));
        $this->line($outcome(str_pad('', 49) . str_pad("Total Income: $totalIncome", 23)));

        return $roundData;
    }

    /**
     * Define the command's schedule.
     *
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
