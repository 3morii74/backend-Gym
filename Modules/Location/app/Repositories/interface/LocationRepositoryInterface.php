<?php

namespace Modules\Location\Repositories\interface;

interface LocationRepositoryInterface
{
    public function allActiveNationality($request);
    public function allNationality($request);
    public function getCountryFromNationality($request);
    public function searchAllActiveNationality($request);
    public function searchAllNationality($request);
    public function storeNationality($request);
    public function updateNationality($request);
    public function deleteNationality($request);

    public function allActiveCountry($request);
    public function allCountry($request);
    public function getStateFromCountry($request);

    public function searchAllActiveCountry($request);
    public function searchAllCountry($request);
    public function storeCountry($request);
    public function updateCountry($request);
    public function deleteCountry($request);

    public function allActiveState($request);
    public function allState($request);
    public function getCityFromState($request);
    public function searchAllActiveState($request);
    public function searchAllState($request);
    public function storeState($request);
    public function updateState($request);
    public function deleteState($request);


    public function allActiveCity($request);
    public function allCity($request);
    public function searchAllActiveCity($request);
    public function searchAllCity($request);
    public function storeCity($request);
    public function updateCity($request);
    public function deleteCity($request);


}
