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

namespace HopeSeekr\PrisonersDilemma\State;

// @todo Evolve Prison Sentences, too
// @todo https://github.com/hopeseekr/PrisonersDilemma/issues/2
class PrisonSentence implements CriminalCode
{
    // @todo The English descriptions could sure use some work.
    public const CONVICTION_LOITERING = 'loitering';
    public const CONVICTION_SINGLE = 'single';
    public const CONVICTION_TEAM = 'team';

    protected array $SENTENCES = [
        self::CONVICTION_SINGLE    => 3.0,
        self::CONVICTION_TEAM      => 2.0,
        self::CONVICTION_LOITERING => 1.0,
    ];

    // @FIXME: So much duplicated code should really be fixed:
    // @FIXME: https://github.com/phpexpertsinc/php-evolver/issues/2
    public function isValidSentence(int $sentenceId): bool
    {
        return array_key_exists($sentenceId, $this->SENTENCES);
    }

    public function getSentence($sentenceId): float
    {
        return $this->SENTENCES[$sentenceId];
    }

    public function getMaxSentence(): float
    {
        return max($this->SENTENCES);
    }
}
