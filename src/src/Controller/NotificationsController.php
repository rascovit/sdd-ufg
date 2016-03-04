<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\NotificationsTable $Notifications
 */
class NotificationsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $notifications = $this->Notifications->find()
            ->where([
                'Notifications.user_id' => $this->loggedUser->id
            ])
            ->orderAsc('is_read')
            ->orderDesc('id');

        $selectedNotification = $this->request->query('notification');
        if($selectedNotification !== null && is_numeric($selectedNotification)) {
            $selectedNotification = $this->Notifications->find()
                ->where([
                    'Notifications.id' => $selectedNotification
                ])->first();
            if($selectedNotification !== null) {
                $this->set('selectedNotification', $selectedNotification);
            }
        }

        $this->set('notifications', $this->paginate($notifications));
        $this->set('_serialize', ['notifications']);
    }

    /**
     * View method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $notification = $this->Notifications->get($id);
        $notification->is_read = true;
        $this->Notifications->save($notification);
        return $this->redirect($notification->link);
    }
}
