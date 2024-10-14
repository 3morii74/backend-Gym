<?php

namespace Modules\Location\Repositories;

use Modules\Location\Models\Nationality;
use Modules\Location\Models\Country;
use Modules\Location\Models\State;
use Modules\Location\Models\City;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface
{
    public function allActiveNationality($request)
    {
        return Nationality::where('status', 'active')->get();
    }

    public function allNationality($request)
    {
        return Nationality::all();
    }

    public function getCountryFromNationality($request)
    {
        return Country::where('nationality_id', $request->nationality_id)->get();
    }

    public function searchAllActiveNationality($request)
    {
        return Nationality::where('status', 'active')
            ->where('name', 'like', '%' . $request->search . '%')
            ->get();
    }

    public function searchAllNationality($request)
    {
        return Nationality::where('name', 'like', '%' . $request->name . '%')
            ->get();
    }

    public function storeNationality($request)
    {
        return Nationality::create($request->all());
    }

    public function updateNationality($request)
    {
        $nationality = Nationality::find($request->id);
        if ($nationality) {
            $nationality->update($request->all());
        }
        return $nationality;
    }

    public function deleteNationality($request)
    {
        $nationality = Nationality::find($request->id);
        if ($nationality) {
            $nationality->delete();
        }
        return $nationality;
    }

    public function allActiveCountry($request)
    {
        return Country::where('status', 'active')->get();
    }

    public function allCountry($request)
    {
        return Country::all();
    }

    public function getStateFromCountry($request)
    {
        return State::where('country_id', $request->country_id)->get();
    }

    public function searchAllActiveCountry($request)
    {
        return Country::where('status', 'active')
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();
    }

    public function searchAllCountry($request)
    {
        return Country::where('name', 'like', '%' . $request->name . '%')
            ->get();
    }

    public function storeCountry($request)
    {
        return Country::create($request->all());
    }

    public function updateCountry($request)
    {
        $country = Country::find($request->id);
        if ($country) {
            $country->update($request->all());
        }
        return $country;
    }

    public function deleteCountry($request)
    {
        $country = Country::find($request->id);
        if ($country) {
            $country->delete();
        }
        return $country;
    }

    public function allActiveState($request)
    {
        return State::where('status', 'active')->get();
    }

    public function allState($request)
    {
        return State::all();
    }

    public function getCityFromState($request)
    {
        return City::where('state_id', $request->state_id)->get();
    }

    public function searchAllActiveState($request)
    {
        return State::where('status', 'active')
            ->where('name', 'like', '%' . $request->search . '%')
            ->get();
    }

    public function searchAllState($request)
    {
        return State::where('name', 'like', '%' . $request->search . '%')
            ->get();
    }

    public function storeState($request)
    {
        return State::create($request->all());
    }

    public function updateState($request)
    {
        $state = State::find($request->id);
        if ($state) {
            $state->update($request->all());
        }
        return $state;
    }

    public function deleteState($request)
    {
        $state = State::find($request->id);
        if ($state) {
            $state->delete();
        }
        return $state;
    }

    public function allActiveCity($request)
    {
        return City::where('status', 'active')->get();
    }

    public function allCity($request)
    {
        return City::all();
    }

    public function searchAllActiveCity($request)
    {
        return City::where('status', 'active')
            ->where('name', 'like', '%' . $request->search . '%')
            ->get();
    }

    public function searchAllCity($request)
    {
        return City::where('name', 'like', '%' . $request->search . '%')
            ->get();
    }

    public function storeCity($request)
    {
        return City::create($request->all());
    }

    public function updateCity($request)
    {
        $city = City::find($request->id);
        if ($city) {
            $city->update($request->all());
        }
        return $city;
    }

    public function deleteCity($request)
    {
        $city = City::find($request->id);
        if ($city) {
            $city->delete();
        }
        return $city;
    }
}
