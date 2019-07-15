# yii2-fillmodel

Automatic fill new model attributes with last saved model. 

Last attributes stored in session

### Installation

Add the package to your `composer.json`:

```
{
    "require": {
        "ofilin/yii2-fillmodel": "^0.1"
    }
}
```

and run `composer update` or alternatively run `composer require ofilin/yii2-fillmodel:^0.1`

### Usage
In action create, add `Fill::model($model, ['attribute_1', 'attribute_2', 'attribute_3'])`;
```
public function actionCreate()
{
    $model = new Posts();
    
    // Add this line:
    \ofilin\fillmodel\Fill::model($model, ['groups', 'scheduled_date', 'scheduled_time']);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['index']);
    }
    return $this->render('create', [
        'model' => $model,
    ]);
}
```

