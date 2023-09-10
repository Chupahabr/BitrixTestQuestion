<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Highloadblock\HighloadBlockTable as HL,
    Bitrix\Main\UI\Extension,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Engine\Response\AjaxJson,
    Bitrix\Main\Error,
    Bitrix\Main\ErrorCollection,
    Bitrix\Main\Engine\Contract\Controllerable;

class CustomForm extends CBitrixComponent implements Controllerable
{
    /**
     * Подключение языковых файлов
     *
     * @return void
     */
    public function onIncludeComponentLang(): void
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    /**
     * Проверка подключения модулей
     *
     * @return void
     * @throws Main\LoaderException
     */
    public function checkModules(): void
    {
        if (!Main\Loader::includeModule('highloadblock'))
            throw new Main\LoaderException(Loc::getMessage('ERROR_MESSAGE_HL_MODULE'));
    }

    /**
     * Проверка заполненности обязательных параметров
     *
     * @return void
     * @throws Main\ArgumentNullException
     */
    protected function checkParams(): void
    {
        if ($this->arParams['HL_BLOCK_ID'] <= 0)
            throw new Main\ArgumentNullException('HL_BLOCK_ID');
    }

    /**
     * Подключение библиотеки bootstrap
     *
     * @return void
     * @throws Main\LoaderException
     */
    protected function includeBootstrap(): void
    {
        Extension::load('ui.bootstrap4');
    }

    /**
     * Собираем поля HL для формирования полей формы
     *
     * @return void
     * @throws Main\SystemException
     */
    public function getResult(): void
    {
        $fields = $this->getHLFields((int)$this->arParams['HL_BLOCK_ID']);
        // Удаляем поле ID
        unset($fields['ID']);

        foreach($fields as $fieldName => $field) {
            $this->arResult['FIELDS'][$fieldName]['NAME'] = Loc::getMessage($fieldName);
            $this->arResult['FIELDS'][$fieldName]['IS_REQUIRED'] = $field->isRequired();
            $this->arResult['FIELDS'][$fieldName]['TYPE'] = $field->getDataType();
        }

        $this->includeComponentTemplate();
    }

    /**
     * Получение сущности HL
     *
     * @param int $hlId
     *
     * @return Main\Entity\Base
     * @throws Main\SystemException
     */
    protected function getHLEntry(int $hlId): Main\Entity\Base
    {
        $hlBlock = HL::getById($hlId)->fetch();

        return HL::compileEntity($hlBlock);
    }

    /**
     * Получение списка полей
     *
     * @param int $hlId
     *
     * @return mixed
     * @throws Main\SystemException
     */
    protected function getHLFields(int $hlId): mixed
    {
        return $this->getHLEntry($hlId)->getFields();
    }

    /**
     * Создание записи в HL
     *
     * @param array  $dataForm
     * @param int    $hlId
     * @param string $mailEvent
     *
     * @return AjaxJson|bool
     * @throws Main\LoaderException
     * @throws Main\SystemException
     */
    public function createEntryAction(array $dataForm, int $hlId, string $mailEvent): AjaxJson | bool
    {
        $this->checkModules();

        $HLEntry = $this->getHLEntry($hlId);
        $result = $HLEntry->getDataClass()::add($dataForm);

        if ($result->isSuccess()) {
            $this->mailTo($mailEvent, $dataForm);

            return $result->isSuccess();
        }

        $error = new Error($result->getErrorMessages());
        $errorCollection = new ErrorCollection([$error]);

        return AjaxJson::createError($errorCollection);
    }

    /**
     * Отправка почтового сообщения
     *
     * @param string $mailEvent
     * @param array  $fields
     *
     * @return void
     */
    protected function mailTo(string $mailEvent, array $fields): void
    {
        \CEvent::Send($mailEvent, SITE_ID, $fields);
    }

    public function executeComponent(): void
    {
        try {
            $this->onIncludeComponentLang();
            $this->checkModules();
            $this->checkParams();
            $this->getResult();
            $this->includeBootstrap();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

    public function configureActions(): array
    {
        return [
            'createEntry' => [
                'prefilters' => [],
            ],
        ];
    }
}