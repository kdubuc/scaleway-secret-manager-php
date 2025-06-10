<?php

namespace Kdubuc\ScwSecretManager\Object;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

abstract class AbstractObject implements ObjectInterface
{
    /**
     * @return static
     */
    public static function fromArray(array $data) : ObjectInterface
    {
        /** @var static */
        return self::getSerializer()->denormalize($data, static::class);
    }

    public function jsonSerialize() : mixed
    {
        return $this->getSerializer()->normalize($this);
    }

    #[Ignore]
    private static function getSerializer() : Serializer
    {
        // Need to pass a class metadata factory with a loader to the normalizer when reading mapping
        // information like Ignore or Groups.
        $classMetadataFactory = new ClassMetadataFactory(
            loader: new AttributeLoader()
        );

        $extractor = new PropertyInfoExtractor(
            listExtractors: [],
            typeExtractors: [
                new ConstructorExtractor(),
                new PhpDocExtractor(),
                new ReflectionExtractor(),
            ],
            descriptionExtractors: [],
            accessExtractors: [],
            initializableExtractors: [],
        );

        return new Serializer(
            normalizers: [
                new ArrayDenormalizer(),
                new BackedEnumNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(
                    classMetadataFactory: $classMetadataFactory,
                    nameConverter: null,
                    propertyAccessor: null,
                    propertyTypeExtractor: $extractor,
                    classDiscriminatorResolver: null,
                    objectClassResolver: null,
                    defaultContext: [],
                    propertyInfoExtractor: $extractor,
                ),
            ],
            encoders: [
                new JsonEncoder(),
            ]
        );
    }
}
