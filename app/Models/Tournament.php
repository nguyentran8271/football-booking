<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'field_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'registration_deadline',
        'max_teams',
        'players_per_team',
        'entry_fee',
        'prize',
        'banner',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'entry_fee' => 'decimal:2',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function teams()
    {
        return $this->hasMany(TournamentTeam::class);
    }

    public function approvedTeams()
    {
        return $this->hasMany(TournamentTeam::class)->where('status', 'approved');
    }

    public function canRegister()
    {
        if ($this->status != 'upcoming') {
            return false;
        }

        if ($this->registration_deadline && now()->gt($this->registration_deadline)) {
            return false;
        }

        $approvedTeams = $this->teams()->where('status', 'approved')->count();
        if ($approvedTeams >= $this->max_teams) {
            return false;
        }

        return true;
    }
}
