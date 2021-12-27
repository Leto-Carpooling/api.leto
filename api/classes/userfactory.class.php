<?php

    class UserFactory{

        /**
         * Creates the appropriate user object which is either a rider, driver, or admin
         * @param int $userId
         * @return UserInterface|Rider|bool
         */
        public static function createUser($user_id){
            $dbManager = new DbManager();
            $userType = $dbManager->query(User::USER_TABLE, ["user_type"], "id = ?", [$user_id]);

            if($userType === false){
                return false;
            }

            return self::makeUser($userType['user_type'], $user_id);
        }

        /**
         * Returns a user of the specified type
         * @param  string $user_type
         */
        private static function makeUser($user_type, $user_id){
            switch($user_type){
                case "admin":
                    {
                        return new Admin($user_id);
                    }
                case "driver":
                    {
                        return new Driver($user_id);
                    }
                default:
                {
                    return new Rider($user_id);
                }
            }
        }

    }


?>