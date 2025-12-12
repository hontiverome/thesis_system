<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'Users';

    protected $primaryKey = 'UserID';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'SchoolID',
        'FullName',
        'Email',
        'BirthDate',
        'PasswordHash',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'PasswordHash',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
    ];




    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'UserRoles', 'UserID', 'RoleID');
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $needle = strtolower($role);

        return $this->roles->contains(function ($r) use ($needle) {
            return strtolower($r->RoleName) === $needle;
        });
    }

    /**
     * Get the faculty details associated with the user.
     */
    public function facultyDetail()
    {
        return $this->hasOne(FacultyDetail::class, 'UserID');
    }

    /**
     * The groups that belong to the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'GroupMembers', 'StudentUserID', 'GroupID')->withPivot('GroupRole');
    }

    /**
     * Get the submissions for the user.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'UploadedByUserID');
    }

    /**
     * Get the proposal approvals for the user.
     */
    public function proposalApprovals()
    {
        return $this->hasMany(ProposalApproval::class, 'ApprovedUserID');
    }

    /**
     * Get the defense panels for the user.
     */
    public function defensePanels(): BelongsToMany
    {
        return $this->belongsToMany(Defense::class, 'DefensePanel', 'PanelistUserID', 'DefenseID')->withPivot('Status');
    }

    /**
     * Get the groups the user is advising.
     */
    public function groupsAsAdviser(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'GroupAdvisers', 'AdviserUserID', 'GroupID');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'PasswordHash' => 'hashed',
            'BirthDate' => 'date',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
