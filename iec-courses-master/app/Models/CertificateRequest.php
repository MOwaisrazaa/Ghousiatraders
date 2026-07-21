<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
        'lecture_id',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];
    
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];
    
    /**
     * Get the user that requested the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the course for which the certificate was requested.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the lecture for which the certificate was requested.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
    
    /**
     * Get the admin who reviewed the request.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    
    /**
     * Get the certificate issued for this request.
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
    
    /**
     * Check if the request is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if the request is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if the request is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
