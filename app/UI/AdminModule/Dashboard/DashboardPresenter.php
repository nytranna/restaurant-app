<?php

namespace App\UI\Admin\Dashboard;

use Nette;

final class DashboardPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private \App\Model\Facade\UserFacade $userFacade,
            private \App\Model\Facade\ReservationFacade $reservationFacade
    ) {
        
    }

//    public function handleRefreshAlerts(): void {
//        $this->template->newReservations = $this->reservationFacade->getAll(['new' => 1]);
//        if ($this->isAjax()) {
//            $this->redrawControl('newReservationsAlerts');
//        } else {
//            $this->redirect('this');
//        }
//    }

    public function renderDefault(): void {
        //\Tracy\Debugger::dump($this->user->identity->data['username']);
          
        $newReservations = $this->reservationFacade->getAll(['is_new' => 1]);
        $this->template->newReservations = $newReservations;
        
//        if (!empty($newReservations)) {
//            $link = $this->link('Reservation:default');
//            $this->flashMessage('Byla vytvořena nová rezervace. <a href="' . $link . '">Přejít do rezervací.</a>', 'info');
//        }
        
    }
}
