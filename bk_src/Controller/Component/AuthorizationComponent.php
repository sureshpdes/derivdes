<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class AuthorizationComponent extends Component
{

    // The other component your component uses
    public $components = ['Auth'];

    /**
     * check if users is authorized
     */
    public function isAuthorized($permission = null, $roles = null, $assignData = true)
    {

        $controller = $this->_registry->getController();


        if (empty($permission)) {
            throw new NotFoundException(__('There was no authorization to verify!'));
        }

        if (empty($roles)) {
            $currentUserId = $this->Auth->user()["id"];
            $roles = TableRegistry::get("AdminsRoles")->find('all')->where([
                'AdminsRoles.admin_id' => $currentUserId
            ]);
        }

        $authArray = [];

        $rolesArray = $roles->toArray();
        foreach ($roles as $role) {

            $RolesPermissions = TableRegistry::get('RolesPermissions');
            $query = $RolesPermissions->find('list', [
                'keyField' => 'permission.id',
                'valueField' => 'permission.slug'
            ])
                ->where(['RolesPermissions.role_id' => $role->role_id])
                ->contain(['Permissions']);
            $results = $query->all();
            $authorizations = $results->toArray();

//            debugger::dumb($authArray);
            array_push($authArray, $authorizations);

        }
        $flat_array = array();
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($authArray)) as $k => $v) {
            $flat_array[$k] = $v;
        }

        // pass permissions back to controller
        if (!empty($controller) && $assignData) {
            // set variables to the view
            $controller->set('permission', $permission);
            $controller->set('authorizations', $flat_array);
        }

        // check if user has required permission
        $authorized = false;
        if (in_array($permission, $flat_array)) {
            $authorized = true;
        }

        return $authorized;
    }
}

?>