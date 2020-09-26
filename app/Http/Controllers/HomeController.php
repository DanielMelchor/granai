<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return view('nueva_agenda');
        //return view('welcome');
        return view('home');
    }

    public function inicio()
    {
        return view('home');
    }

    public function calendario(){
        /*
        google calendar api client id 176819823333-iuq86ltdn66vh9j8pi2btr9brf3hhe7f.apps.googleusercontent.com
        google calendar api client pass HApUaAQ4M0IdCTsoWprb4nX9
        */
        $m = ''; // mensajes de error
        $id_event = ''; //id evento creado
        $link_event;

        if (isset($_POST['agendar'])) {
            date_default_timezone_set('America/Guatemala');
            include_once('google-api-php-client-2.2.4/vendor/autoload.php');
            putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');

            $client = new Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes(['https://www.googleapis.com/auth/calendar']);

            $id_calendar = '176819823333-iuq86ltdn66vh9j8pi2btr9brf3hhe7f.apps.googleusercontent.com';

            $datetime_start = new Datetime($_POST['date_start']);
            $datetime_end   = new Datetime($_POST['date_start']);

            $time_end = $datetime_end->add(new DateInterval('PT1H'));

            $time_start = $datetime_start->format(\DateTime::RFC3339);
            $time_end   = $datetime_end->format(\DateTime::RFC3339);

            $nombre = (isset($_POST['username']))?$_POST['username']:' xyz ';

            try{
                $calendarService = Google_Service_Calendar($client);

                $optParams = array(
                    'orderBy' => 'startTime',
                    'maxResults' => 20,
                    'singleEvents' => TRUE,
                    'timeMin' => $time_start,
                    'timeMax' => $time_end,
                );

                $events = $calendarService->events->listEvents($id_calendar, $optParams);
                $cont_events = count($events->getItems());

                if ($cont_events == 0) {
                    $event = new Google_Service_Calendar_Event();
                    $event->setSummary('Cita con el paciente '. $nombre);
                    $event->setDescription('Revision, Tratamiento');

                    $start = new Google_Service_Calendar_EventDateTime();
                    $start->setDateTime($time_start);
                    $start->setStart($start);

                    $end = new Google_Service_Calendar_EventDateTime();
                    $end->setDateTime($time_end);
                    $end->setStart($end);

                    $createdEvent = $calendarService->events->insert($id_calendar, $event);
                    $id_event = $createdEvent->getId();
                    $link_event = $createdEvent->gethtmlLink();
                }else{
                    $m = "hay ".$cont_events." eventos en ese rango de fechas";
                }
            } catch(Google_Service_Exception $gs){
                $m = json_decode($gs->getMessage());
                $m = $m->error->message;
            } catch(Exception $e){
                $m = $e->getMessage();
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return view('auth.login');
    }
}
