<?php

class User
{

    private $Table = "users";
    private $id, $referred_by, $provider_id, $user_type, $name, $email, $email_verified_at, $verification_code, $new_email_verificiation_code, $password, $remember_token, $device_token, $avatar, $avatar_original, $address, $country, $city, $postal_code, $phone, $balance, $banned, $referral_code, $customer_package_id, $remaining_uploads, $created_at, $updated_at;
    private $conn;


    public function __construct($con, $id)
    {
        $this->conn = $con;
        $this->id = $id;

        $stmt = $this->conn->prepare("SELECT `id`, `referred_by`, `provider_id`, `user_type`, `name`, `email`, `email_verified_at`, `verification_code`, `new_email_verificiation_code`, `password`, `remember_token`, `device_token`, `avatar`, `avatar_original`, `address`, `country`, `city`, `postal_code`, `phone`, `balance`, `banned`, `referral_code`, `customer_package_id`, `remaining_uploads`, `created_at`, `updated_at` FROM " . $this->Table . " WHERE id = ? ");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($this->id, $this->referred_by, $this->provider_id, $this->user_type, $this->name, $this->email, $this->email_verified_at, $this->verification_code, $this->new_email_verificiation_code, $this->password, $this->remember_token, $this->device_token, $this->avatar, $this->avatar_original, $this->address, $this->country, $this->city, $this->postal_code, $this->phone, $this->balance, $this->banned, $this->referral_code, $this->customer_package_id, $this->remaining_uploads, $this->created_at, $this->updated_at);

        while ($stmt->fetch()) {
            $this->id = $this->id;
            $this->referred_by = $this->referred_by;
            $this->provider_id = $this->provider_id;
            $this->user_type = $this->user_type;
            $this->name = $this->name;
            $this->email = $this->email;
            $this->email_verified_at = $this->email_verified_at;
            $this->verification_code = $this->verification_code;
            $this->new_email_verificiation_code = $this->new_email_verificiation_code;
            $this->password = $this->password;
            $this->remember_token = $this->remember_token;
            $this->device_token = $this->device_token;
            $this->avatar = $this->avatar;
            $this->avatar_original = $this->avatar_original;
            $this->address = $this->address;
            $this->country = $this->country;
            $this->city = $this->city;
            $this->postal_code = $this->postal_code;
            $this->phone = $this->phone;
            $this->balance = $this->balance;
            $this->banned = $this->banned;
            $this->referral_code = $this->referral_code;
            $this->customer_package_id = $this->customer_package_id;
            $this->remaining_uploads = $this->remaining_uploads;
            $this->created_at = $this->created_at;
            $this->updated_at = $this->updated_at;
        }
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getReferredBy()
    {
        return $this->referred_by;
    }

    /**
     * @return mixed
     */
    public function getProviderId()
    {
        return $this->provider_id;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getEmailVerifiedAt()
    {
        return $this->email_verified_at;
    }

    /**
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->verification_code;
    }

    /**
     * @return mixed
     */
    public function getNewEmailVerificiationCode()
    {
        return $this->new_email_verificiation_code;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * @return mixed
     */
    public function getDeviceToken()
    {
        return $this->device_token;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getAvatarOriginal()
    {
        return $this->avatar_original;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return mixed
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @return mixed
     */
    public function getReferralCode()
    {
        return $this->referral_code;
    }

    /**
     * @return mixed
     */
    public function getCustomerPackageId()
    {
        return $this->customer_package_id;
    }

    /**
     * @return mixed
     */
    public function getRemainingUploads()
    {
        return $this->remaining_uploads;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }




}

?>