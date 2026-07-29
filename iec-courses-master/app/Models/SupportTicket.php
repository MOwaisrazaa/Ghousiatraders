<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'customer_name',
        'customer_email',
        'order_id',
        'department_id',
        'subject',
        'priority',
        'status',
        'assigned_agent_id',
        'satisfaction_rating',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function department()
    {
        return $this->belongsTo(SupportDepartment::class, 'department_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id')->latestOfMany();
    }

    public function firstMessage()
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id')->oldestOfMany();
    }

    /**
     * Relative human readable updated time (e.g., "10 mins ago", "1 hour ago").
     */
    public function getRelativeUpdatedAttribute()
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

    /**
     * Initials for customer avatar.
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->customer_name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
        }
        return strtoupper(substr($this->customer_name, 0, 2));
    }
}
