<?php

namespace ofilin\fillmodel;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;

class Fill
{

    private static $sub_key = '_fill';

    /**
     * @param ActiveRecord $model
     * @param array|null $attributes
     * @throws InvalidConfigException
     */
    public static function model(ActiveRecord &$model, array $attributes = null)
    {
        if ($attributes == null) {
            throw new InvalidConfigException('$attributes required, available attributes: ' . implode(', ', $model->attributes()));
        }
        $session = self::getSession($model);

        $clone = clone $model; // Clone need, but errors returned

        if ($clone->load(Yii::$app->request->post())) {
            if (!$clone->validate()) { // if not valid data, do not save to session
                return;
            }
            foreach ($attributes as $attribute) {
                $session[$attribute] = $clone->{$attribute};
            }
            self::setSession($model, $session);
        } else {
            foreach ($attributes as $attribute) {
                $model->{$attribute} = isset($session[$attribute]) && empty($model->{$attribute}) ? $session[$attribute] : null;
            }
        }
        return;
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function getSession($model)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        if (!$session->has(self::getClassName($model))) {
            $session->set(self::getClassName($model), []);
        }
        return $session->get(self::getClassName($model));
    }

    /**
     * @param $model
     * @param $data
     */
    public static function setSession($model, $data)
    {
        $session = Yii::$app->session;
        return $session->set(self::getClassName($model), $data);
    }

    /**
     * @param $obj
     * @return string
     */
    public static function getClassName($obj)
    {
        return get_class($obj) . self::$sub_key;
    }
}
