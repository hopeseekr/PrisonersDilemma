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

class Suspect
{
    protected SuspectGenome $genome;

    public function __construct(SuspectGenome $genome, float $mutationRate = 0.10)
    {
        $this->genome = $genome;

        $genome->actionWeight += (int) round(random_int(-100, 200) * $mutationRate);
    }

    public function takeAction(): int
    {
        // New chance to randomly learn a new action.
        $chosenDecisionId = (int) round(
            (random_int(0, count($this->genome->actions) * 100) + $this->genome->actionWeight) / 100
        );

        dump([
            count($this->genome->actions) * 100,
            $this->genome->actionWeight,
        ]);

        if (SuspectDecision::isValidDecision($chosenDecisionId)) {
            $decision = SuspectDecision::POSSIBLE_DECISIONS[$chosenDecisionId];
            $this->genome->rememberAction($chosenDecisionId, $decision);
        }

        return $chosenDecisionId;
    }
}
