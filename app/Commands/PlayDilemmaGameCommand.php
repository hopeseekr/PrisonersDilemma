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

use Illuminate\Console\Scheduling\Schedule;
use InvalidArgumentException;
use LaravelZero\Framework\Commands\Command;
use PHPExperts\ConsolePainter\ConsolePainter;

class PlayDilemmaGameCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'play';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Play the Prisoner\'s Dilemma thought experiment.';

    const CONFESS = 0;
    const TESTIFY = 1;
    const SILENCE = 2;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $choices = [
            'Confess',
            'Testify against your partner',
            'Say nothing',
        ];

        $cp = new ConsolePainter();
        $warning = $cp->onRed()->bold()->yellow();
        $this->line($warning('                                                  '));
        $this->line($warning('   You and your partner have been arrested!       '));
        $this->line($warning('                                                  '));
        $this->line('');

        $rules = $cp->onBlack()->bold()->white();
        $this->line($rules('   If you testify against him, you will probably  '));
        $this->line($rules('   receive a much lighter sentence.               '));
        $this->line($rules('                                                  '));
        $this->line($rules('   If he testifies against you, you will probably '));
        $this->line($rules('   receive a much harder sentence.                '));
        $this->line($rules('                                                  '));
        $this->line('');

        $this->line($cp->onBlue()->bold()->white('   Here are your options:   '));
        $rules = $cp->bold();
        foreach ($choices as $index => $choice) {
            $this->line('   ' . $cp->onLightCyan()->bold()->blue("[$index]") . ' ' . $rules($choice));
        }
        $this->line('');

        while (true) {
            try {
                $decision = $this->ask($cp->onBlue()->bold()->white('   What is your decision?   '));
                if (filter_var($decision, FILTER_VALIDATE_INT) === false || ($decision < 0 || $decision > 2)) {
                    throw new InvalidArgumentException();
                }

                break;
            } catch (InvalidArgumentException $e) {
            }
        }

        $play = function ($decision) use ($choices) {
            $RANDOMNESS = 100; // 100 = 0 chance.
            $sentences = [
                'loitering' => 1,
                'single'    => 3,
                'team'      => 2,
            ];

            $partnerChoice = random_int(1, 2);
            $partnerDecisions = [
                self::CONFESS => 'They confessed to everything',
                self::TESTIFY => 'They testified against you',
                self::SILENCE => 'They said nothing',
            ];
            $partnerDecision = $partnerDecisions[$partnerChoice];
            $this->line((new ConsolePainter())->onRed()->white()->bold("   Your partner's decision: $partnerDecision.   "));
            $choices = [
                'Confess',
                'Testify against your partner',
                'Say nothing',
            ];

            switch ($decision) {
                case self::CONFESS:
                    $convictions['you'] = $sentences['single'];
                    $convictions['partner'] = $partnerChoice === 0 ? $sentences['single'] : 0;
                    break;
                case self::TESTIFY:
                    if ($partnerChoice === self::CONFESS) {
                        $convictions['you'] = 0;
                        $convictions['partner'] = $sentences['single'];
                    } elseif ($partnerChoice === self::TESTIFY) {
                        $convictions['partner'] = $convictions['you'] = $sentences['team'];
                    } elseif ($partnerChoice === self::SILENCE) {
                        $convictions['you'] = 0;
                        $convictions['partner'] = $sentences['single'];
                    }
                    break;
                case self::SILENCE:
                    if ($partnerChoice === self::CONFESS) {
                        $convictions['you'] = 0;
                        $convictions['partner'] = $sentences['single'];
                    } elseif ($partnerChoice === self::TESTIFY) {
                        $convictions['you'] = $sentences['single'];
                        $convictions['partner'] = 0;
                    } elseif ($partnerChoice === self::SILENCE) {
                        $convictions['partner'] = $convictions['you'] = $sentences['loitering'];
                    }
            }

            return $convictions;
        };

        $sentences = [
            'loitering' => 1,
            'single'    => 3,
            'team'      => 2,
        ];

        $convictions = $play($decision);
        dump($convictions);

        $calculateIncome = function ($hourlyWage, $years): float {
            return 2000 * $hourlyWage * $years;
        };
        $hourlyWage = 10;

        $this->line($cp->onBlack()->bold()->yellow('   Minimum Wage: ')->red("\$$hourlyWage"));

        $years = $convictions['you'] === 1 ? 'year' : 'years';
        $yourConviction = $convictions['you'] === 0 ? 'acquitted and are now free!' : "convicted for $convictions[you] $years.";
        $years = $convictions['partner'] === 1 ? 'year' : 'years';
        $partnerConviction = $convictions['partner'] === 0 ? 'acquitted and is now free!' : "convicted for $convictions[partner] $years.";

        $yourIncome = '$' . number_format($calculateIncome($hourlyWage, max($sentences) - $convictions['you']));
        $partnerIncome = '$' . number_format($calculateIncome($hourlyWage, max($sentences) - $convictions['partner']));
        $totalIncome = '$' . number_format($calculateIncome($hourlyWage, (max($sentences) * 2) - $convictions['you'] - $convictions['partner']));

        $outcome = $cp->onBlue()->bold();
        $this->line($outcome(str_pad('   Outcomes:', 72)));
        $this->line($outcome(str_pad("      - You were $yourConviction", 55) . str_pad("Income: $yourIncome", 17)));
        $this->line($outcome(str_pad("      - Your partner was $partnerConviction", 55) . str_pad("Income: $partnerIncome", 17)));
        $this->line($outcome(str_pad('', 49) . str_pad("Total Income: $totalIncome", 23)));
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
