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

    public function renderDefault(): void {

        $newReservations = $this->reservationFacade->getAll(['is_new' => 1]);
        $this->template->newReservations = $newReservations;
    }
}
