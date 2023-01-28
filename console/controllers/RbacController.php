<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $createAdmin = $auth->createPermission('createAdmin');
        $createAdmin->description = 'Create a Admin';
        $auth->add($createAdmin);

        $readAdmin = $auth->createPermission('readAdmin');
        $readAdmin->description = 'Read a Admin';
        $auth->add($readAdmin);

        $listAdmin = $auth->createPermission('listAdmin');
        $listAdmin->description = 'List a Admin';
        $auth->add($listAdmin);

        $updateAdmin = $auth->createPermission('updateAdmin');
        $updateAdmin->description = 'Update a Admin';
        $auth->add($updateAdmin);

        $deleteAdmin = $auth->createPermission('deleteAdmin');
        $deleteAdmin->description = 'Delete a Admin';
        $auth->add($deleteAdmin);

        $createClient = $auth->createPermission('createClient');
        $createClient->description = 'Create a Client';
        $auth->add($createClient);

        $readClient = $auth->createPermission('readClient');
        $readClient->description = 'Read a Client';
        $auth->add($readClient);

        $listClient = $auth->createPermission('listClient');
        $listClient->description = 'List a Client';
        $auth->add($listClient);

        $updateClient = $auth->createPermission('updateClient');
        $updateClient->description = 'Update a Client';
        $auth->add($updateClient);

        $deleteClient = $auth->createPermission('deleteClient');
        $deleteClient->description = 'Delete a Client';
        $auth->add($deleteClient);

        $createEmployee = $auth->createPermission('createEmployee');
        $createEmployee->description = 'Create a Employee';
        $auth->add($createEmployee);

        $readEmployee = $auth->createPermission('readEmployee');
        $readEmployee->description = 'Read a Employee';
        $auth->add($readEmployee);

        $listEmployee = $auth->createPermission('listEmployee');
        $listEmployee->description = 'List a Employee';
        $auth->add($listEmployee);

        $updateEmployee = $auth->createPermission('updateEmployee');
        $updateEmployee->description = 'Update a Employee';
        $auth->add($updateEmployee);

        $deleteEmployee = $auth->createPermission('deleteEmployee');
        $deleteEmployee->description = 'Delete a Employee';
        $auth->add($deleteEmployee);

        $createTicket = $auth->createPermission('createTicket');
        $createTicket->description = 'Create a Ticket';
        $auth->add($createTicket);

        $readTicket = $auth->createPermission('readTicket');
        $readTicket->description = 'Read a Ticket';
        $auth->add($readTicket);

        $listTicket = $auth->createPermission('listTicket');
        $listTicket->description = 'List a Ticket';
        $auth->add($listTicket);

        $updateTicket = $auth->createPermission('updateTicket');
        $updateTicket->description = 'Update a Ticket';
        $auth->add($updateTicket);

        $deleteTicket = $auth->createPermission('deleteTicket');
        $deleteTicket->description = 'Delete a Ticket';
        $auth->add($deleteTicket);

        $createAirport = $auth->createPermission('createAirport');
        $createAirport->description = 'Create a Airport';
        $auth->add($createAirport);

        $readAirport = $auth->createPermission('readAirport');
        $readAirport->description = 'Read a Airport';
        $auth->add($readAirport);

        $listAirport = $auth->createPermission('listAirport');
        $listAirport->description = 'List a Airport';
        $auth->add($listAirport);

        $updateAirport = $auth->createPermission('updateAirport');
        $updateAirport->description = 'Update a Airport';
        $auth->add($updateAirport);

        $deleteAirport = $auth->createPermission('deleteAirport');
        $deleteAirport->description = 'Delete a Airport';
        $auth->add($deleteAirport);

        $createAirplane = $auth->createPermission('createAirplane');
        $createAirplane->description = 'Create a Airplane';
        $auth->add($createAirplane);

        $readAirplane = $auth->createPermission('readAirplane');
        $readAirplane->description = 'Read a Airplane';
        $auth->add($readAirplane);

        $listAirplane = $auth->createPermission('listAirplane');
        $listAirplane->description = 'List a Airplane';
        $auth->add($listAirplane);

        $updateAirplane = $auth->createPermission('updateAirplane');
        $updateAirplane->description = 'Update a Airplane';
        $auth->add($updateAirplane);

        $deleteAirplane = $auth->createPermission('deleteAirplane');
        $deleteAirplane->description = 'Delete a Airplane';
        $auth->add($deleteAirplane);

        $createFlight = $auth->createPermission('createFlight');
        $createFlight->description = 'Create a Flight';
        $auth->add($createFlight);

        $readFlight = $auth->createPermission('readFlight');
        $readFlight->description = 'Read a Flight';
        $auth->add($readFlight);

        $listFlight = $auth->createPermission('listFlight');
        $listFlight->description = 'List a Flight';
        $auth->add($listFlight);

        $updateFlight = $auth->createPermission('updateFlight');
        $updateFlight->description = 'Update a Flight';
        $auth->add($updateFlight);

        $deleteFlight = $auth->createPermission('deleteFlight');
        $deleteFlight->description = 'Delete a Flight';
        $auth->add($deleteFlight);

        $createTariff = $auth->createPermission('createTariff');
        $createTariff->description = 'Create a Tariff';
        $auth->add($createTariff);

        $readTariff = $auth->createPermission('readTariff');
        $readTariff->description = 'Read a Tariff';
        $auth->add($readTariff);

        $listTariff = $auth->createPermission('listTariff');
        $listTariff->description = 'List a Tariff';
        $auth->add($listTariff);

        $updateTariff = $auth->createPermission('updateTariff');
        $updateTariff->description = 'Update a Tariff';
        $auth->add($updateTariff);

        $deleteTariff = $auth->createPermission('deleteTariff');
        $deleteTariff->description = 'Delete a Tariff';
        $auth->add($deleteTariff);

        $createBalanceReq = $auth->createPermission('createBalanceReq');
        $createBalanceReq->description = 'Create a BalanceReq';
        $auth->add($createBalanceReq);

        $readBalanceReq = $auth->createPermission('readBalanceReq');
        $readBalanceReq->description = 'Read a BalanceReq';
        $auth->add($readBalanceReq);

        $listBalanceReq = $auth->createPermission('listBalanceReq');
        $listBalanceReq->description = 'List a BalanceReq';
        $auth->add($listBalanceReq);

        $updateBalanceReq = $auth->createPermission('updateBalanceReq');
        $updateBalanceReq->description = 'Update a BalanceReq';
        $auth->add($updateBalanceReq);

        $deleteBalanceReq = $auth->createPermission('deleteBalanceReq');
        $deleteBalanceReq->description = 'Delete a BalanceReq';
        $auth->add($deleteBalanceReq);

        $createConfig = $auth->createPermission('createConfig');
        $createConfig->description = 'Create a Config';
        $auth->add($createConfig);

        $readConfig = $auth->createPermission('readConfig');
        $readConfig->description = 'Read a Config';
        $auth->add($readConfig);

        $listConfig = $auth->createPermission('listConfig');
        $listConfig->description = 'List a Config';
        $auth->add($listConfig);

        $updateConfig = $auth->createPermission('updateConfig');
        $updateConfig->description = 'Update a Config';
        $auth->add($updateConfig);

        $deleteConfig = $auth->createPermission('deleteConfig');
        $deleteConfig->description = 'Delete a Config';
        $auth->add($deleteConfig);

        $createRefund = $auth->createPermission('createRefund');
        $createRefund->description = 'Create a Refund';
        $auth->add($createRefund);

        $readRefund = $auth->createPermission('readRefund');
        $readRefund->description = 'Read a Refund';
        $auth->add($readRefund);

        $listRefund = $auth->createPermission('listRefund');
        $listRefund->description = 'List a Refund';
        $auth->add($listRefund);

        $updateRefund = $auth->createPermission('updateRefund');
        $updateRefund->description = 'Update a Refund';
        $auth->add($updateRefund);

        $deleteRefund = $auth->createPermission('deleteRefund');
        $deleteRefund->description = 'Delete a Refund';
        $auth->add($deleteRefund);

        $createReceipt = $auth->createPermission('createReceipt');
        $createReceipt->description = 'Create a Receipt';
        $auth->add($createReceipt);

        $readReceipt = $auth->createPermission('readReceipt');
        $readReceipt->description = 'Read a Receipt';
        $auth->add($readReceipt);

        $listReceipt = $auth->createPermission('listReceipt');
        $listReceipt->description = 'List a Receipt';
        $auth->add($listReceipt);

        $updateReceipt = $auth->createPermission('updateReceipt');
        $updateReceipt->description = 'Update a Receipt';
        $auth->add($updateReceipt);

        $deleteReceipt = $auth->createPermission('deleteReceipt');
        $deleteReceipt->description = 'Delete a Receipt';
        $auth->add($deleteReceipt);
        // roles
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $supervisor = $auth->createRole('supervisor');
        $auth->add($supervisor);

        $ticketOperator = $auth->createRole('ticketOperator');
        $auth->add($ticketOperator);

        $client = $auth->createRole('client');
        $auth->add($client);

        $auth->addChild($client, $readClient);
        $auth->addChild($client, $updateClient);
        $auth->addChild($client, $createTicket);
        $auth->addChild($client, $readTicket);
        $auth->addChild($client, $listTicket);
        $auth->addChild($client, $deleteTicket);
        $auth->addChild($client, $readAirport);
        $auth->addChild($client, $listAirport);
        $auth->addChild($client, $readFlight);
        $auth->addChild($client, $listFlight);
        $auth->addChild($client, $readTariff);
        $auth->addChild($client, $createBalanceReq);
        $auth->addChild($client, $readBalanceReq);
        $auth->addChild($client, $listBalanceReq);
        $auth->addChild($client, $deleteBalanceReq);
        $auth->addChild($client, $readConfig);
        $auth->addChild($client, $listConfig);
        $auth->addChild($client, $createRefund);
        $auth->addChild($client, $readRefund);
        $auth->addChild($client, $listRefund);
        $auth->addChild($client, $deleteRefund);
        $auth->addChild($client, $createReceipt);
        $auth->addChild($client, $readReceipt);
        $auth->addChild($client, $updateReceipt);
        $auth->addChild($client, $listReceipt);
        $auth->addChild($client, $deleteReceipt);

        $auth->addChild($ticketOperator, $readEmployee);
        $auth->addChild($ticketOperator, $readClient);
        $auth->addChild($ticketOperator, $listClient);
        $auth->addChild($ticketOperator, $readTicket);
        $auth->addChild($ticketOperator, $updateTicket);
        $auth->addChild($ticketOperator, $listTicket);
        $auth->addChild($ticketOperator, $readAirport);
        $auth->addChild($ticketOperator, $listAirport);
        $auth->addChild($ticketOperator, $readAirplane);
        $auth->addChild($ticketOperator, $listAirplane);
        $auth->addChild($ticketOperator, $readFlight);
        $auth->addChild($ticketOperator, $listFlight);
        $auth->addChild($ticketOperator, $readTariff);
        $auth->addChild($ticketOperator, $listTariff);
        $auth->addChild($ticketOperator, $readConfig);
        $auth->addChild($ticketOperator, $listConfig);

        $auth->addChild($supervisor, $ticketOperator);
        $auth->addChild($supervisor, $updateClient);
        $auth->addChild($supervisor, $deleteClient);
        $auth->addChild($supervisor, $createTariff);
        $auth->addChild($supervisor, $deleteTariff);
        $auth->addChild($supervisor, $updateTariff);
        $auth->addChild($supervisor, $updateBalanceReq);
        $auth->addChild($supervisor, $deleteBalanceReq);
        $auth->addChild($supervisor, $readBalanceReq);
        $auth->addChild($supervisor, $listBalanceReq);
        $auth->addChild($supervisor, $createConfig);
        $auth->addChild($supervisor, $updateConfig);
        $auth->addChild($supervisor, $deleteConfig);
        $auth->addChild($supervisor, $updateRefund);
        $auth->addChild($supervisor, $listRefund);
        $auth->addChild($supervisor, $readRefund);
        $auth->addChild($supervisor, $listReceipt);
        $auth->addChild($supervisor, $readReceipt);

        $auth->addChild($admin, $supervisor);
        $auth->addChild($admin, $createAdmin);
        $auth->addChild($admin, $readAdmin);
        $auth->addChild($admin, $listAdmin);
        $auth->addChild($admin, $updateAdmin);
        $auth->addChild($admin, $deleteAdmin);
        $auth->addChild($admin, $createEmployee);
        $auth->addChild($admin, $listEmployee);
        $auth->addChild($admin, $updateEmployee);
        $auth->addChild($admin, $deleteEmployee);
        $auth->addChild($admin, $createAirport);
        $auth->addChild($admin, $updateAirport);
        $auth->addChild($admin, $deleteAirport);
        $auth->addChild($admin, $createAirplane);
        $auth->addChild($admin, $updateAirplane);
        $auth->addChild($admin, $deleteAirplane);
        $auth->addChild($admin, $updateFlight);
        $auth->addChild($admin, $createFlight);
        $auth->addChild($admin, $deleteFlight);
    }
}
