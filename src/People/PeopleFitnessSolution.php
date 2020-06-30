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

namespace HopeSeekr\PrisonersDilemma\People;

use PHPExperts\GAO\Solution;

class PeopleFitnessSolution extends Solution
{
    const TEMPERMENT_NAIVE = 0;
    const TEMPERMENT_SELFISH = 100;

    public function genome()
    {
        // @todo: If there's ever a way to evolve unique Actions, then this should be -maxDecisions to +maxDecisions.

        // Like all real-world genomes, most GA genomes aren't semantically indexed either lol.
        return [
            ['integer', -200, 200], // Action/Decision Weight; upper and lower bounds
        ];
    }

    public function evaluate($fitness)
    {
        if (!($fitness instanceof PersonFitnessScore)) {
            dd($fitness);
            throw new \LogicException('Something is messed up. Corrupted entity.');
        }

        return $fitness->getFitnessScore();
    }
}
