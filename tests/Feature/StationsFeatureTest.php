<?php

namespace glorifiedking\BusTravel\Tests;

use Artisan;
use glorifiedking\BusTravel\Station;
use glorifiedking\BusTravel\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StationsFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test availability of stations route.
     */
    public function testDenyGuestAccess()
    {
        //$this->withoutExceptionHandling();
        $response = $this->get('/transit/routes');
        $response->assertStatus(302);
    }

    public function testDenyUserWithoutPermissions()
    {  Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/transit/routes');
        $response->assertStatus(403);
    }

    public function testListStations()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $station = factory(Station::class)->create();
        $response = $this->actingAs($user)->get('/transit/stations');
        $response->assertStatus(200);
        $response->assertSee($station->name);
        $response->assertViewHas('bus_stations', Station::all());
    }

    public function testDenyGuestStationCreateForm()
    {
        $response = $this->get('/transit/stations/create');
        $response->assertStatus(302);
    }

    public function testDenyStationsCreateForm()
    {
       Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
       $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/transit/stations/create');
        $response->assertStatus(403);
    }

    public function testStationsCreateForm()
    {
        Artisan::call('db:seed', ['--class' => 'glorifiedking\BusTravel\Seeds\PermissionSeeder']);
        $user = factory(User::class)->create();
        $user->assignRole('BT Super Admin');
        $response = $this->actingAs($user)->get('/transit/stations/create');
        $response->assertStatus(200);
        $response->assertSee('station_name');
    }
}
