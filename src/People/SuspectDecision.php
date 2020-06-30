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

class SuspectDecision
{
    // @todo ADD support for "Lawyering up".
    public const CONFESS = 0;
    public const TESTIFY = 1;
    public const SILENCE = 2;

    // @todo: Research whether this should be a constant or evolvable.
    public const POSSIBLE_DECISIONS = [
        self::CONFESS => 'Confess',
        self::TESTIFY => 'Testify against your partner',
        self::SILENCE => 'Say nothing',
    ];

    // @FIXME: Holy crap! This should NOT be a static method!
    public static function isValidDecision(int $decisionId): bool
    {
        return array_key_exists($decisionId, self::POSSIBLE_DECISIONS);
    }

    /**
     * Returns a third-person response for a chosen decision.
     */
    public static function getThirdPartyResponse(int $decisionId): string
    {
        $DECISIONS = [
            self::CONFESS => 'They confessed to everything',
            self::TESTIFY => 'They testified against you',
            self::SILENCE => 'They said nothing',
        ];

        if (!array_key_exists($decisionId, $DECISIONS)) {
            throw new \LogicException("Invalid Partner Decision: $decisionId");
        }

        return $DECISIONS[$decisionId];
    }
}
