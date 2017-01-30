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
     * crear evento
     *
     * Método para crear un evento en el calendario
     *
     * @param $data Array de data a enviar para el evento.
     * @return string POST response
     */
    public function createEvent($data);

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
     * salvar Token
     * 
     * URL de callback en donde se mandará el token par utilizar la API
     *
     * @return string POST response
     */
    public function saveToken();

    /**
     * get eventos
     *
     * Método para obtener todos los eventos en un período de tiempo
     *
     * @param $startDate fecha y hora de inicio de período a solicitar
     * @param $endDate fecha y hora final de período a solicitar
     * @param $timezone timezone del período a solicitar
     * @return string POST response
     */
    public function getEvents($startDate, $endDate, $timezone);

    /**
     * get busy method
     * 
     * Método que retorna los bloques busy del calendario en un período de tiempo
     *
     * @param $startDate fecha y hora de inicio de período a solicitar
     * @param $endDate fecha y hora final de período a solicitar
     * @param $timezone timezone del período a solicitar
     * @return string POST response
     */
    public function getBusy($startDate, $endDate, $timezone);

}