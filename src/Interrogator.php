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

namespace HopeSeekr\PrisonersDilemma;

class Interrogator
{
    public const CONFESS = 0;
    public const TESTIFY = 1;
    public const SILENCE = 2;

    public const CONVICTION_LOITERING = 'loitering';
    public const CONVICTION_SINGLE = 'single';
    public const CONVICTION_TEAM = 'team';

    protected int $partnerChoice;

    public function interrogatePartner(): int
    {
        //$this->partnerChoice = random_int(1, 2);
        $this->partnerChoice = (new Suspect(new SuspectGenome()))->takeAction();

        return $this->partnerChoice;
    }
}
