<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_name',
        'captain_name',
        'phone',
        'logo',
        'players_list',
        'status',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
