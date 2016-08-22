<?php
namespace VichImagineBundle\Upload;

use Gaufrette\Exception\FileNotFound;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class File extends AbstractServiceSetter
{
	private $mapping;
	
	public function __construct(ContainerInterface $container)
	{
		parent::setContainer($container);
		
		$this->mapping = $this->getContainer()->get('vich_uploader.property_mapping_factory');
	}

	/**
	 * @param $entity
	 * @param $mapping_name
	 *
	 * @return PropertyMapping
	 */
	protected function getMapping($entity, $mapping_name)
	{
		$mapping = $this->mapping->fromObject($entity, NULL, $mapping_name);

		if (count($mapping) == 0) {
			throw new Exception("error getting property mapping");
		}

		return $mapping[0];
	}

	public function asset($entity, $mapping_name, $default = false)
	{
		$mapping = $this->getMapping($entity, $mapping_name);
		$file = $this->getContainer()->get('vich_uploader.templating.helper.uploader_helper')->asset($entity, $mapping->getFilePropertyName());

		return $file ? $file : $default;
	}

	public function fileName($entity, $mapping_name)
	{
		$mapping = $this->getMapping($entity, $mapping_name);

		return $mapping->getFileName($entity);
	}
}
