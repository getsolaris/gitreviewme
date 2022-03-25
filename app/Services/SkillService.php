<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SkillService
 * @package App\Services
 */
class SkillService
{
    protected Skill $skill;
    public function __construct(Skill $skill) {
        $this->skill = $skill;
    }

    /**
     * @param User|Project $model
     * @param string $name
     * @return Collection|array
     */
    public function create(User | Project $model, array $requestSkills): Collection|array
    {

        foreach ($requestSkills as $requestSkill) {
            if ($this->morphByModelExistToSkill($model, $requestSkill)) {
                return $model->skills;
            }

            $skillModel = $this->skill->whereName($requestSkill);
            if ($skillModel->exists()) {
                $model->skills()->attach($skillModel->first()->id);
            } else {
                $skill = new Skill;
                $skill->name = strtolower($requestSkill);
                $model->skills()->save($skill);
            }
        }

        return $model->skills;
    }

    /**
     * @param User|Project $model
     * @param int $id
     * @return int
     */
    public function destroy(User | Project $model, int $id): int
    {
        return $model->skills()->detach($id);
    }

    /**
     * @param User|Project $model
     * @param string $name
     * @return bool
     */
    private function morphByModelExistToSkill(User | Project $model, string $name): bool
    {
        return $model->skills()->where('name', $name)->exists();
    }
}
