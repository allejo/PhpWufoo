<?php

namespace allejo\Wufoo;

use allejo\Wufoo\Exceptions\SubmissionException;

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
     * Get the details of this form.
     *
     * @api
     *
     * @param bool $includeTodayCount Set to true to include the number of entries received today.
     *
     * @since 0.1.0
     *
     * @return mixed
     */
    public function getDetails($includeTodayCount = false)
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}.json');

        $result = self::$client
            ->get($url, [
                'query' => 'includeTodayCount=' . ($includeTodayCount) ? 'true' : 'false'
            ])
            ->getBody();

        $json = json_decode($result, true);

        return $json['Forms'][0];
    }

    /**
     * Get the fields in this form.
     *
     * @api
     *
     * @param bool $getSystem Set to true to receive
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getFields($getSystem = false)
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/fields.json');
        $params = [
            'system' => ($getSystem === true) ? 'true' : null
        ];

        self::prepareQueryParameters($params);

        $result = self::$client
            ->get($url, [
                'query' => self::buildQuery($params)
            ])
            ->getBody();

        $json = json_decode($result, true);

        return $json['Fields'];
    }

    /**
     * Get any comments made on this form's entries.
     *
     * @api
     *
     * @param int|null $entryID Get comments for only a specific entry
     * @param int|null $offset  The offset of comments that
     * @param int|null $limit   The number comments returned in the request (maximum of 100)
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getComments($entryID = null, $offset = null, $limit = null)
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/comments.json');
        $params = [
            'entryId' => $entryID,
            'pageStart' => $offset,
            'pageSize' => $limit
        ];

        self::prepareQueryParameters($params);

        $result = self::$client
            ->get($url, [
                'query' => self::buildQuery($params)
            ])
            ->getBody();

        $json = json_decode($result, true);

        return $json['Comments'];
    }

    /**
     * Get the number of comments made on this form's entries.
     *
     * @api
     *
     * @since 0.1.0
     *
     * @return int
     */
    public function getCommentCount()
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/comments/count.json');

        $result = self::$client
            ->get($url)
            ->getBody();

        $json = json_decode($result, true);

        return $json['Count'];
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
     * @api
     *
     * @since 0.1.0
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

    /**
     * Submit an entry to this Wufoo form.
     *
     * @api
     *
     * @param  array $formData An array containing the data that will be POST'd to the Wufoo form. The keys used in the
     *                         the array should be the unique IDs (e.g. Field1, Field12).
     *
     * @since  0.1.0
     *
     * @throws SubmissionException Wufoo returned an error on the entry submission.
     *
     * @return mixed
     */
    public function submitEntry(array $formData)
    {
        $url = $this->buildUrl('https://{subdomain}.wufoo.com/api/v3/forms/{identifier}/entries.json');
        $result = self::$client
            ->post($url, [
                'form_params' => $formData
            ])
            ->getBody();
        $json = json_decode($result, true);

        if ($json['Success'] == 0)
        {
            throw new SubmissionException($json);
        }

        return $json['EntryId'];
    }

    /**
     * Get details of all the forms under this account.
     *
     * @api
     *
     * @param bool $includeTodayCount Set to true to include the number of entries received today.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public static function getForms($includeTodayCount = false)
    {
        $url = self::interpolate('https://{subdomain}.wufoo.com/api/v3/forms.json', [
            'subdomain' => self::$subdomain
        ]);
        $params = [
            'includeTodayCount' => ($includeTodayCount) ? 'true' : null
        ];

        self::prepareQueryParameters($params);

        $result = self::$client
            ->get($url, [
                'query' => self::buildQuery($params)
            ])
            ->getBody();

        $json = json_decode($result, true);

        return $json['Forms'];
    }
}
