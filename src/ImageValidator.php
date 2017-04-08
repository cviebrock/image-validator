<?php namespace Cviebrock\ImageValidator;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageValidator extends Validator
{

    /**
     * Creates a new instance of ImageValidator
     *
     * @param \Illuminate\Contracts\Translation\Translator $translator
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function __construct(
        Translator $translator,
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }

    /**
     * Usage: image_size:width[,height]
     *
     * @param  $attribute  string
     * @param  $value      string|array
     * @param  $parameters array
     * @return boolean
     */
    public function validateImageSize($attribute, $value, $parameters)
    {
        $image = $this->getImagePath($value);

        // Get the image dimension info, or fail.

        $image_size = @getimagesize($image);
        if ($image_size === false) {
            return false;
        }

        // If only one dimension rule is passed, assume it applies to both height and width.

        if (!isset($parameters[1])) {
            $parameters[1] = $parameters[0];
        }

        // Parse the parameters.  Options are:
        //
        // 	"300" or "=300"   - dimension must be exactly 300 pixels
        // 	"<300"            - dimension must be less than 300 pixels
        // 	"<=300"           - dimension must be less than or equal to 300 pixels
        // 	">300"            - dimension must be greater than 300 pixels
        // 	">=300"           - dimension must be greater than or equal to 300 pixels

        $width_check = $this->checkDimension($parameters[0], $image_size[0]);
        $height_check = $this->checkDimension($parameters[1], $image_size[1]);

        return $width_check['pass'] && $height_check['pass'];
    }

    /**
     * Build the error message for validation failures.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    public function replaceImageSize($message, $attribute, $rule, $parameters)
    {
        $width = $height = $this->checkDimension($parameters[0]);
        if (isset($parameters[1])) {
            $height = $this->checkDimension($parameters[1]);
        }

        return str_replace(
            [':width', ':height'],
            [$width['message'], $height['message']],
            $message
        );
    }

    /**
     * Usage: image_aspect:ratio
     *
     * @param  $attribute  string
     * @param  $value      string|array
     * @param  $parameters array
     * @return boolean
     * @throws \RuntimeException
     */
    public function validateImageAspect($attribute, $value, $parameters)
    {
        $image = $this->getImagePath($value);

        // Get the image dimension info, or fail.

        $image_size = @getimagesize($image);
        if ($image_size === false) {
            return false;
        }

        $image_width = $image_size[0];
        $image_height = $image_size[1];

        // Parse the parameter(s).  Options are:
        //
        // 	"0.75"   - one param: a decimal ratio (width/height)
        // 	"3,4"    - two params: width, height
        //
        // If the first value is prefixed with "~", the orientation doesn't matter, i.e.:
        //
        // 	"~3,4"   - would accept either "3:4" or "4:3" images

        $both_orientations = false;

        if (substr($parameters[0], 0, 1) === '~') {
            $parameters[0] = substr($parameters[0], 1);
            $both_orientations = true;
        }

        if (count($parameters) === 1) {
            $aspect_width = $parameters[0];
            $aspect_height = 1;
        } else {
            $aspect_width = (int) $parameters[0];
            $aspect_height = (int) $parameters[1];
        }

        if ($aspect_width === 0 || $aspect_height === 0) {
            throw new RuntimeException('Aspect is zero or infinite: ' . $parameters[0]);
        }

        $check = ($image_width * $aspect_height) / $aspect_width;

        if ((int) round($check) === $image_height) {
            return true;
        }

        if ($both_orientations) {
            $check = ($image_width * $aspect_width) / $aspect_height;
            if ((int) round($check) === $image_height) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the error message for validation failures.
     *
     * @param  string $message
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    public function replaceImageAspect($message, $attribute, $rule, $parameters)
    {
        return str_replace(':aspect', implode(':', $parameters), $message);
    }

    /**
     * Parse the dimension rule and check if the dimension passes the rule.
     *
     * @param  string $rule
     * @param  integer $dimension
     * @return array
     * @throws \RuntimeException
     */
    protected function checkDimension($rule, $dimension = 0)
    {

        $dimension = (int) $dimension;

        if ($rule === '*') {
            $message = $this->translator->trans('image-validator::validation.anysize');
            $pass = true;
        } else if (preg_match('/^(\d+)\-(\d+)$/', $rule, $matches)) {
            $size1 = (int) $matches[1];
            $size2 = (int) $matches[2];
            $message = $this->translator->trans('image-validator::validation.between', compact('size1', 'size2'));
            $pass = ($dimension >= $size1) && ($dimension <= $size2);
        } else if (preg_match('/^([<=>]*)(\d+)$/', $rule, $matches)) {

            $size = (int) $matches[2];

            switch ($matches[1]) {
                case '>':
                    $message = $this->translator->trans('image-validator::validation.greaterthan', compact('size'));
                    $pass = $dimension > $size;
                    break;
                case '>=':
                    $message = $this->translator->trans('image-validator::validation.greaterthanorequal',
                        compact('size'));
                    $pass = $dimension >= $size;
                    break;
                case '<':
                    $message = $this->translator->trans('image-validator::validation.lessthan', compact('size'));
                    $pass = $dimension < $size;
                    break;
                case '<=':
                    $message = $this->translator->trans('image-validator::validation.lessthanorequal', compact('size'));
                    $pass = $dimension <= $size;
                    break;
                case '=':
                case '':
                    $message = $this->translator->trans('image-validator::validation.equal', compact('size'));
                    $pass = $dimension == $size;
                    break;
                default:
                    throw new RuntimeException('Unknown image size validation rule: ' . $rule);
            }
        } else {
            throw new RuntimeException('Unknown image size validation rule: ' . $rule);
        }

        return compact('message', 'pass');
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function getImagePath($value)
    {
        // if were passed an instance of UploadedFile, return the path
        if ($value instanceof UploadedFile) {
            return $value->getPathname();
        }

        // if we're passed a PHP file upload array, return the "tmp_name"
        if (is_array($value) && array_get($value, 'tmp_name') !== null) {
            return $value['tmp_name'];
        }

        // fallback: we were likely passed a path already
        return $value;
    }
}
