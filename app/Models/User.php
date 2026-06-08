<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
		'name',
		'nisn',
		'nip',
		'email',
		'password',
		'role_id',
		'is_active',
	];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
			'is_active' => 'boolean',
        ];
    }

	public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'author_id', 'id');
    }

    public function mediaUploads()
    {
        return $this->hasMany(Media::class, 'uploader_id', 'id');
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'author_id', 'id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'user_id', 'id');
    }

    public function verifiedRegistrations()
    {
        return $this->hasMany(Registration::class, 'verified_by', 'id');
    }

    public function verifiedRegistrationDocuments()
    {
        return $this->hasMany(RegistrationDocument::class, 'verified_by', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'user_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }
}
