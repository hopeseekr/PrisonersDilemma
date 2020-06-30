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

use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * @property float $individualWages
 * @property float $communityWages
 * @property float $selfishWeight    1.0 == 100% selfish, don't care about community.
 *                                   > 1.0 == Sociopathic. Antipathy.
 *                                   < 0.0 == Pathologically altruistic (Group > Self)
 */
class PersonFitnessScore extends SimpleDTO
{
    public function getFitnessScore()
    {
        return $this->individualWages + ($this->communityWages -  ($this->communityWages * $this->selfishWeight));
    }
}
