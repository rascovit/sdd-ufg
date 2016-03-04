<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Table\NotificationsTable;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $helpers = ['Gravatar.Gravatar'];

    /** @var bool|User */
    protected $loggedUser = false;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'login',
                        'password' => 'password'
                    ]
                ]
            ],
            'authorize' => ['Controller'],
            'authError' => __('Você não possui permissão para acessar esta página'),
            'flash' => [
                'element' => 'Elements/Flash/warning'
            ],
            'loginRedirect' => '/',
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ]
        ]);

        $this->setLoggedUserData();
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * Defines the authorization to access the controller pages.
     *
     * @param $user User authenticated.
     * @return bool True if the user has permission or false otherwise.
     */
    public function isAuthorized($user)
    {
        // Admin can access all actions
        if ($this->loggedUser !== false && $this->loggedUser->canAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Gets the logged user data, if exists, and sets to all controllers.
     */
    private function setLoggedUserData()
    {
        $authUser = isset($this->request->Session()->read('Auth')['User']) ?
            $this->request->Session()->read('Auth')['User'] : false;

        if($authUser === false) {
            return;
        }

        $userModel = $this->loadModel('Users');

        $this->loggedUser = $userModel->get($authUser['id'], [
            'contain' => ['Teachers.Roles', 'Notifications']
        ]);

        $this->set('loggedUser', $this->loggedUser);
    }
}
