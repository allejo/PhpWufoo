<?php

namespace allejo\Wufoo;

/**
 * @api
 * @since 0.1.0
 */
class WufooForm extends ApiObject
{
    /**
     * Create an object for interacting with a Wufoo form.
     *
     * **Warning:** Keep in mind, when using the URL-friendly name, that value will change if you rename the form. It is
     * recommended you use the unique ID of the form.
     *
     * @api
     *
     * @param string $id The unique ID of the Wufoo form or URL-friendly name.
     *
     * @since 0.1.0
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the entries belonging to this form.
     *
     * **Warning:**
     * - Data in fields that are marked as “Admin Only” are not returned via the API.
     * - Data from "hidden" and encrypted fields will be shown
     *
     * @api
     *
     * @param  EntryQuery|null $query When set to null, 25 entries will be retrieved (a limit imposed by Wufoo). Use an
     *                                EntryQuery object to have more control on what entries to receive and how.
     *
     * @since  0.1.0
     *
     * @return mixed
     */
    public function getEntries(EntryQuery $query = null)
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/entries.json');
        $result = self::$client
            ->get($url, [
                'query' => (string)$query
            ])
            ->getBody();

        $json = json_decode($result, true);

        return $json['Entries'];
    }

    /**
     * Get the number of entries this Wufoo form has.
     *
     * @return int
     */
    public function getEntriesCount()
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/entries/count.json');
        $result = self::$client->get($url)->getBody();
        $json = json_decode($result, true);

        return $json['EntryCount'];
    }
}
