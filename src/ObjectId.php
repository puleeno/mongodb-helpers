<?php
namespace Puleeno\MongoDB\Helpers;

class ObjectId
{
    const KEY_LENGTH = 24;

    /**
     * This is needed to keep documents unique that have the same timestamp.
     * @var integer
     * @see $timestamp
     */
    public static $_mongoIdFromTimestampCounter = 0;

    /**
     * Mongo Id From Timestamp
     * @param integer $timestamp
     * @return string
     * @see http://docs.mongodb.org/manual/reference/object-id/
     */
    public static function createMongoIdFromTimestamp($timestamp)
    {
        // Build Binary Id
        $binaryTimestamp = pack('N', $timestamp); // unsigned long
        $machineId = substr(md5(gethostname()), 0, 3); // 3 bit machine identifier
        $binaryPID = pack('n', getmypid()); // unsigned short
        $counter = substr(pack('N', self::$_mongoIdFromTimestampCounter++), 1, 3); // Counter

        $binaryId = "{$binaryTimestamp}{$machineId}{$binaryPID}{$counter}";

        // Convert to ASCII
        $id = '';
        for ($i = 0; $i < 12; $i++) {
            $id .= sprintf("%02X", ord($binaryId[$i]));
        }

        // Return Mongo ID
        return $id;
    }

    public static function length() {
        return static::KEY_LENGTH;
    }
}
