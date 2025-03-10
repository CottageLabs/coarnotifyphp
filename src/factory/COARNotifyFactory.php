<?php

namespace coarnotify\factory;

use coarnotify\core\activitystreams2\ActivityStream;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\patterns\accept\Accept;
//use coarnotify\patterns\AnnounceEndorsement;
//use coarnotify\patterns\AnnounceRelationship;
//use coarnotify\patterns\AnnounceReview;
//use coarnotify\patterns\AnnounceServiceResult;
//use coarnotify\patterns\Reject;
//use coarnotify\patterns\RequestEndorsement;
//use coarnotify\patterns\RequestReview;
//use coarnotify\patterns\TentativelyAccept;
//use coarnotify\patterns\TentativelyReject;
//use coarnotify\patterns\UnprocessableNotification;
//use coarnotify\patterns\UndoOffer;
use coarnotify\exceptions\NotifyException;

class COARNotifyFactory
{
    /**
     * The list of model classes recognized by this factory
     */
    private static $MODELS = [
        Accept::class,
//        AnnounceEndorsement::class,
//        AnnounceRelationship::class,
//        AnnounceReview::class,
//        AnnounceServiceResult::class,
//        Reject::class,
//        RequestEndorsement::class,
//        RequestReview::class,
//        TentativelyAccept::class,
//        TentativelyReject::class,
//        UnprocessableNotification::class,
//        UndoOffer::class
    ];

    /**
     * Get the model class based on the supplied types. The returned callable is the class, not an instance.
     *
     * @param string|array $incomingTypes A single type or list of types. If a list is provided, ALL types must match a candidate
     * @return string|null A class representing the best fit for the supplied types, or null if no match
     */
    public static function getByTypes($incomingTypes): ?string
    {
        if (!is_array($incomingTypes)) {
            $incomingTypes = [$incomingTypes];
        }

        $candidate = null;
        $candidateFit = null;

        foreach (self::$MODELS as $model) {
            $documentTypes = $model::TYPE;
            if (!is_array($documentTypes)) {
                $documentTypes = [$documentTypes];
            }
            if (array_intersect($documentTypes, $incomingTypes) === $documentTypes) {
                $fit = count($incomingTypes) - count($documentTypes);
                if ($candidateFit === null || abs($fit) < abs($candidateFit)) {
                    $candidate = $model;
                    $candidateFit = $fit;
                    if ($fit === 0) {
                        return $candidate;
                    }
                }
            }
        }

        return $candidate;
    }

    /**
     * Get an instance of a model based on the data provided.
     *
     * @param array $data The raw stream data to parse and instantiate around
     * @param mixed ...$args Any args to pass to the object constructor
     * @return NotifyPattern A NotifyPattern of the correct type, wrapping the data
     * @throws NotifyException If a model cannot be found that matches the data
     */
    public static function getByObject(array $data, ...$args): ?NotifyPattern
    {
        $stream = new ActivityStream($data);
        $types = $stream->getProperty(Properties::TYPE);
        if ($types === null) {
            throw new NotifyException("No type found in object");
        }

        $class = self::getByTypes($types);
        if ($class !== null) {
            return new $class($data, ...$args);
        }

        return null;
    }

    /**
     * Register a new model class with the factory.
     *
     * @param string $model The model class to register
     */
    public static function register(string $model)
    {
        $existing = self::getByTypes($model::TYPE);
        if ($existing !== null) {
            self::$MODELS = array_filter(self::$MODELS, fn($m) => $m !== $existing);
        }
        self::$MODELS[] = $model;
    }
}