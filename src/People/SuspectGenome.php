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

class SuspectGenome
{
    public array $actions = [];
    public int $actionWeight = 0;

    public function rememberAction(int $actionId, string $actionDescription)
    {
        // @todo: Should probably add error handling if the action doesn't actually exist.
        $this->actions[$actionId] = $actionDescription;
    }
}
