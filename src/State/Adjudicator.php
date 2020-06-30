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

use HopeSeekr\PrisonersDilemma\People\SuspectDecision;

class Adjudicator
{
    protected CriminalCode $criminalCode;

    public function __construct(CriminalCode $criminalCode = null)
    {
        if (!$criminalCode) {
            $criminalCode = new PrisonSentence();
        }

        $this->criminalCode = $criminalCode;
    }

    // @todo: Maybe add more than one partner in the future?
    public function issueSentence(int $decisionId, int $partnerChoiceId): array
    {
        $cc = $this->criminalCode;

        // @FIXME: This needs to use the Strategy Pattern.
        $convictions = [];
        switch ($decisionId) {
            case SuspectDecision::CONFESS:
                $convictions['you'] = $cc->getSentence(PrisonSentence::CONVICTION_SINGLE);
                $convictions['partner'] = $partnerChoiceId === 0 ? $cc->getSentence(PrisonSentence::CONVICTION_SINGLE) : 0;
                break;
            case SuspectDecision::TESTIFY:
                if ($partnerChoiceId === SuspectDecision::CONFESS) {
                    $convictions['you'] = 0;
                    $convictions['partner'] = $cc->getSentence(PrisonSentence::CONVICTION_SINGLE);
                } elseif ($partnerChoiceId === SuspectDecision::TESTIFY) {
                    $convictions['partner'] = $convictions['you'] = $cc->getSentence(PrisonSentence::CONVICTION_TEAM);
                } elseif ($partnerChoiceId === SuspectDecision::SILENCE) {
                    $convictions['you'] = 0;
                    $convictions['partner'] = $cc->getSentence(PrisonSentence::CONVICTION_SINGLE);
                }
                break;
            case SuspectDecision::SILENCE:
                if ($partnerChoiceId === SuspectDecision::CONFESS) {
                    $convictions['you'] = 0;
                    $convictions['partner'] = $cc->getSentence(PrisonSentence::CONVICTION_SINGLE);
                } elseif ($partnerChoiceId === SuspectDecision::TESTIFY) {
                    $convictions['you'] = $cc->getSentence(PrisonSentence::CONVICTION_SINGLE);
                    $convictions['partner'] = 0;
                } elseif ($partnerChoiceId === SuspectDecision::SILENCE) {
                    $convictions['partner'] = $convictions['you'] = $cc->getSentence(PrisonSentence::CONVICTION_LOITERING);
                }
                break;
            default:
                throw new \LogicException("An unknown decision was given: $decisionId. MISTRIAL!!");
        }

        return $convictions;
    }
}
