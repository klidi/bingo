<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 4.3.21
 * Time: 7:19 PM
 */

namespace App\Console\Commands\Traits;


use Illuminate\Validation\Validator;

trait AskWisely
{
    protected function askValid($question, $field, $rules)
    {
        $value = $this->ask($question);

        if($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }

    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = validator([
            $fieldName => $value
        ], [
            $fieldName => $rules
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
