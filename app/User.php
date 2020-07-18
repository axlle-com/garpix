<?php

    namespace App;

    use App\Order;
    use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Support\Facades\Storage;

    class User extends Authenticatable
    {
        use Notifiable;

        public const IS_BANNED = 1;
        public const IS_ACTIVE = 0;
        public const IS_ADMIN = 1;
        public const IS_USER = 0;

        protected $dateFormat = 'U';
        protected $fillable = [
            'name', 'email',
        ];
        protected $hidden = [
            'password', 'remember_token',
        ];
        protected $casts = [
            'email_verified_at' => 'datetime',
        ];

        public function orders()
        {
            return $this->hasMany(Order::class);
        }

        public static function create(array $fields):self
        {
            $user = new static;
            $user->fill($fields);
            $user->generatePassword($fields['password']);
            return $user;
        }

        public function edit(array $fields):void
        {
            $this->fill($fields); //name,email
            $this->save();
        }

        public function generatePassword($password):void
        {
            if($password != null){
                $this->password = bcrypt($password);
                $this->save();
            }
        }

        public function remove():void
        {
            $this->removeAvatar();
            $this->delete();
        }

        public function uploadAvatar($image):void
        {
            if($image == null) { return; }
            $this->removeAvatar();
            $filename = uniqid('',false) . '.' . $image->extension();
            $image->storeAs('uploads', $filename);
            $this->avatar = $filename;
            $this->save();
        }

        public function removeAvatar():void
        {
            if($this->avatar != null){
                Storage::delete('uploads/' . $this->avatar);
            }
        }

        public function getImage():string
        {
            return $this->avatar ? '/uploads/' . $this->avatar : '';
        }

        public function makeAdmin()
        {
            $this->is_admin = static::IS_ADMIN;
            $this->save();
        }

        public function makeNormal()
        {
            $this->is_admin = static::IS_USER;
            $this->save();
        }

        public function toggleAdmin($value)
        {
            if($value == null){
                return $this->makeNormal();
            }
            return $this->makeAdmin();
        }

        public function ban()
        {
            $this->status = static::IS_BANNED;
            $this->save();
        }

        public function unban()
        {
            $this->status = static::IS_ACTIVE;
            $this->save();
        }

        public function toggleBan($value)
        {
            if($value == null){
                return $this->unban();
            }
            return $this->ban();
        }
    }
