<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Returns a collection of all team members that are
     * not yet member of this project.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableUsers(): Collection
    {
        return $this->team
            ->users()                                // query all team members (team owner not included)
            ->where('user_id', '!=', $this->user_id) // exclude the current project's owner
            ->whereDoesntHave(                       // exclude all current project members
                'projects',
                fn (Builder $query) => $query->where('projects.id', $this->id)
            )
            ->get()
            // removed unnecessary logic / add team owner if not already in or owning the project
            ->sortBy('name')
            ->values();
    }
}
