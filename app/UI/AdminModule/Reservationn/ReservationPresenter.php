<?php

namespace App\UI\Admin\Reservation;

use App\Model\PostFacade;
use Nette;
use App\Forms\ReservationFormControl;
use App\Forms\ReservationFormFactory;

final class ReservationPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private ReservationFormFactory $reservationFormFactory,
            private \App\Model\Facade\ReservationFacade $reservationFacade
    ) {
        
    }

    public function renderDefault(): void {
        $reservation = $this->reservationFacade->getAll();
        $this->template->reservation = $reservation;
    }

    public function renderEdit($id = null): void {

        if ($id) {
            $reservation = $this->reservationFacade->getOne(['id' => $id]);

            if (!$reservation) {
                $this->error(404);
            }

            $this['reservationForm']->setDefaults($reservation);
        }

        $this->template->reservationData = $reservation ?? null;
    }

    protected function createComponentReservationForm(): ReservationFormControl {
        return $this->reservationFormFactory->create();
    }
}
