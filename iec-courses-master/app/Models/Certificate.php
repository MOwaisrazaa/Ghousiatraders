<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
        'lecture_id',
        'certificate_request_id',
        'certificate_number',
        'file_path',
        'issue_date',
        'expiry_date',
    ];
    
    protected $casts = [
        'issue_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];
    
    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the course for which the certificate was issued.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the lecture for which the certificate was issued.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
    
    /**
     * Get the certificate request that led to this certificate.
     */
    public function certificateRequest()
    {
        return $this->belongsTo(CertificateRequest::class);
    }
    
    /**
     * Check if the certificate has expired.
     */
    public function hasExpired()
    {
        return $this->expiry_date && now()->gt($this->expiry_date);
    }
    
    /**
     * Generate a unique certificate number.
     */
    public static function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . date('Y') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (static::where('certificate_number', $number)->exists());
        
        return $number;
    }
}
