<?php

use common\rbac\UseLeagueOfCommerce;
use dektrium\user\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function safeUp()
    {
        $check = array_diff([ 'user', 'profile', 'social_account', 'token'],$this->db->schema->tableNames);
        if (count($check)) {
            echo PHP_EOL;
            echo 'check exist tables: user, profile, social_account, token' . PHP_EOL;
            echo 'if they do not exist, execute:' . PHP_EOL;
            echo 'php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations' . PHP_EOL;
            echo PHP_EOL;
            return false;
        }
        $check = array_diff([ 'auth_item', 'auth_item_child', 'auth_assignment', 'auth_rule'],$this->db->schema->tableNames);
        if (!count($check)) {

            $auth = Yii::$app->authManager;

            $admin = $auth->createRole('admin');
            $admin->description = 'Администратор';
            $auth->add($admin);

            $trial = $auth->createRole('trial');
            $trial->description = 'Пробный';
            $auth->add($trial);

            $client = $auth->createRole('client');
            $client->description = 'Клиент';
            $auth->add($client);

            $adminPanel = Yii::$app->authManager->createPermission('adminPanel');
            $adminPanel->description = 'Доступ в админ-панель';
            $auth->add($adminPanel);

            $auth->addChild($admin, $adminPanel);
            $rule = new UseLeagueOfCommerce();
            $auth->add($rule);

            $useLeagueOfCommerce = Yii::$app->authManager->createPermission('useLeagueOfCommerce');
            $useLeagueOfCommerce->description = 'Доступ к сервису Лиги торговли';
            $useLeagueOfCommerce->ruleName = $rule->name;
            $auth->add($useLeagueOfCommerce);

            $auth->addChild($admin, $useLeagueOfCommerce);
            $auth->addChild($trial, $useLeagueOfCommerce);
            $auth->addChild($client, $useLeagueOfCommerce);

            $user = new User();
            $user->username = 'admin';
            $user->email = 'admin@yopmail.com';
            $user->password = 'Gmd6mtDeX8zUCXwc';
            $user->confirmed_at = time();
            $user->save();
            $auth->assign($admin, $user->id);

        } else {
            echo PHP_EOL;
            echo 'check exist tables: auth_item, auth_item_child, auth_assignment, auth_rule.' . PHP_EOL;
            echo 'if they do not exist, execute:' . PHP_EOL;
            echo 'php yii migrate --migrationPath=@yii/rbac/migrations/' . PHP_EOL;
            echo PHP_EOL;
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        $this->execute('TRUNCATE TABLE "profile", "user" RESTART IDENTITY CASCADE');
        $this->execute('TRUNCATE TABLE "auth_assignment", "auth_item_child", "auth_item", "auth_rule" RESTART IDENTITY CASCADE');

        return true;
    }
}
