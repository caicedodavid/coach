<?php
namespace App\CalendarAdapters;
/*
 *Interface for the VirtualClassromms adapters
 *
 */
interface CalendarAdapter
{
    /**
     * constructor de la agenda.
     *
     * @param $userToken token del usuario.
     * @param $isPrimary si el calendario del usuario es primario o no
     */
    public function __construct($userToken, $isPrimary);

    /**
     * generar url de autenticación
     *
     * Se genera el url para que el usuario le de permisos a la aplicación para
     * manejar su calendario
     *
     * @return string Url a ser redirigido
     */
    public function generateAuthUrl();

    /**
     * get Token
     * 
     * usando el codigo devuelto por la api, solicitar el token de la api
     *
     * @return string POST response
     */
    public function getToken($code);
    
	/**
     * crear evento
     *
     * Método para crear un evento en el calendario
     *
     * @param $data Array de data a enviar para el evento.
     * @return string POST response
     */
    public function createEvent($data);

    /**
     * list eventos
     *
     * Método para obtener todos los eventos en un período de tiempo
     *
     * @param $startDate fecha y hora de inicio de período a solicitar
     * @param $endDate fecha y hora final de período a solicitar
     * @param $timezone timezone del período a solicitar
     * @return string POST response
     */
    public function listEvents($startDate, $endDate, $timezone);

    /**
     * list method
     * 
     * Método que retorna los bloques busy del calendario en un período de tiempo
     *
     * @param $startDate fecha y hora de inicio de período a solicitar
     * @param $endDate fecha y hora final de período a solicitar
     * @param $timezone timezone del período a solicitar
     * @return string POST response
     */
    public function listBusy($startDate, $endDate, $timezone);

    /**
     * create calendar
     * 
     * Método que retorna un calendario secundario
     *
     * @param $calendarName
     * @return calendarId
     */
    public function createCalendar($calendarName);

}