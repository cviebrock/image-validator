<?php namespace Cviebrock\ImageValidator;

use Illuminate\Validation\Validator;


class ImageValidator extends Validator
{

	/**
	 * Creates a new instance of ImageValidator
	 */
	public function __construct($translator, $data, $rules, $messages)
	{
		parent::__construct($translator, $data, $rules, $messages);
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

		// $value is the path to the image file.  If this is coming from uploads, then
		// we need to grab that from the file upload array.

		if ( is_array($value) && array_get($value, 'tmp_name') !== null) {
			$value = $value['tmp_name'];
		}

		// Get the image dimension info, or fail.

		$image_size = getimagesize( $value );
		if ($image_size===false) return false;


		// If only one dimension rule is passed, assume it applies to both height and width.

		if ( !isset($parameters[1]) )
		{
			$parameters[1] = $parameters[0];
		}

		// Parse the parameters.  Options are:
		//
		// 	"300" or "=300"   - dimension must be exactly 300 pixels
		// 	"<300"            - dimension must be less than 300 pixels
		// 	"<=300"           - dimension must be less than or equal to 300 pixels
		// 	">300"            - dimension must be greater than 300 pixels
		// 	">=300"           - dimension must be greater than or equal to 300 pixels

		$width_check  = $this->checkDimension( $parameters[0], $image_size[0] );
		$height_check = $this->checkDimension( $parameters[1], $image_size[1] );

		return $width_check['pass'] && $height_check['pass'];

	}

	/**
	 * Parse the dimension rule and check if the dimension passes the rule.
	 *
	 * @param  string  $rule
	 * @param  integer $dimension
	 * @return array
	 */
	protected function checkDimension($rule, $dimension=0)
	{

		$dimension = intval($dimension);

		if ($rule == '*')
		{
			$message = $this->translator->trans('image-validator::validation.anysize');
			$pass = true;
		}
		else if ( preg_match('/^(\d+)\-(\d+)$/', $rule, $matches) )
		{
			$size1 = intval($matches[1]);
			$size2 = intval($matches[2]);
			$message = $this->translator->trans('image-validator::validation.between', compact('size1','size2'));
			$pass = ($dimension >= $size1) && ($dimension <= $size2);
		}
		else if ( preg_match('/^([<=>]*)(\d+)$/', $rule, $matches) )
		{

			$size = intval($matches[2]);

			switch ($matches[1])
			{
				case '>':
					$message = $this->translator->trans('image-validator::validation.greaterthan', compact('size'));
					$pass = $dimension > $size;
					break;
				case '>=':
					$message = $this->translator->trans('image-validator::validation.greaterthanorequal', compact('size'));
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
					throw new \RuntimeException('Unknown image size validation rule: ' . $rule );
			}

		}
		else
		{
			throw new \RuntimeException('Unknown image size validation rule: ' . $rule );
		}

		return compact('message','pass');

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

		$width = $height = $this->checkDimension( $parameters[0] );
		if ( isset($parameters[1]) )
		{
			$height = $this->checkDimension( $parameters[1] );
		}

		return str_replace(
			array( ':width', ':height' ),
			array( $width['message'], $height['message'] ),
			$message
		);

	}


}
