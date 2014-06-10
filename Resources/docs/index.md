# Importbundle

## Using the ImportBundle

At the moment only the CSVImport is implemented. The CSVImport is available by a service. Example:

```php
$csvImport = $this->container->get('bluetea.csv_import');
$csvImport->setFilePath($importEntity->getAbsolutePath());
$csvImport->setModule($module);
$csvImport->setImport($importEntity);
$statistics = $csvImport->startImport();
```

First you get the CSVImport service. Then set the filepath, module and import entity. If all is set, start the import and wait!
The startImport method returns an array with statistic (added, updated, deleted, skipped and errors). These statistics can be saved to the database.

## Extend the ImportBundle

The ImportBundle is developed to handle data imports. The ImportBundle consist of factories, import types, import traits and import services.

### Factories

The ImportBundle is capable to parse all kind of files. At the moment only CSV files are supported but it's easy to add more file types by adding factories.

#### What is a factory?

A factory receives a file with a bunch of options (settings from the SystemSettingsBundle) and parses the file. A factory implement the FactoryInterface which only contains a parse method. The parse method returns a two dimensional array which is further handled in the import types.

An example of the CSV factory:

```php
public function parse($file, $options)
{
    if (!file_exists($file)) {
        throw new \Exception("File not found");
    }

    $fileObj = new \SplFileObject($file);
    $fileObj->setFlags(\SplFileObject::READ_CSV);
    $fileObj->setCsvControl(
        $options['delimiter'],
        $options['enclosure'],
        $options['escape']
    );

    return $fileObj;
}
```

### Import Types

The ImportBundle will do nothing without import types. For each entity are one or more import types created which handles the data which is parsed by a factory. An example of the parsed data:

```php
array(
    array(
        'reference',
        'name',
        'another reference for a relationship'
    ),
    array(
        'second reference',
        'second name',
        'another reference for a relationship'
    ),
    array(
        'third reference',
        'third name',
        'another reference for a relationship'
    ),
);
```

The import types implement the ImportInterface and extend the BaseImport class. The BaseImport class implement default business logic like the import, deleteFromCache, persisAndFlush and deleteEntity methods. The import type has only one mandatory method: importLine.

The ImportLine method receives an one-dimensional array. Example:

```php
array(
    'reference',
    'name',
    'another reference for a relationship'
),
```

In the most situations you check if you got all fields by counting them. If the field count is OK you validate the fields you get and after that you start importing the line.

An example (Poli Analyze Appointment Hour Import Type):

```php
public function importLine($line)
{
    if (count($line) == $this->columnNumbers) {
        $this->validateImportLine($line);
    } else {
        // If count is invalid, throw the InvalidLineCountException
        throw new InvalidLineCountException();
    }
    /**
    Put array fields in variables
    Description fields are not imported.
    This information is available within the database due table relations
    */
    list (
        $date,
        $sessionStart,
        $sessionEnd,
        $specialism,
        $staff,
        $agenda,
        $department,
    ) = $line;
    // Import all relationships
    $specialism = $this->importSpecialism($specialism, $specialism, $specialism);
    $staff = $this->importStaff($staff, '', '', $staff);
    $department = $this->importDepartment($department, $department);
    $agenda = $this->importPoliAnalyzeAgenda($agenda, $agenda);
    // Import the PoliAnalyzeAppointment and finalize the import
    $this->finalizeImport(
        $date,
        $sessionStart,
        $sessionEnd,
        $specialism,
        $staff,
        $agenda,
        $department
    );
}
```

The validateImportLine method is a method which we defined in the same class and validates the line.

Example:

```php
protected function validateImportLine($line)
{
    // Validate reference
    if (\DateTime::createFromFormat('Y-m-d', $argument = $line[0]) === false) {
        throw new InvalidArgumentException($argument, 'date', 'date');
    }
    // Validate session start
    if (\DateTime::createFromFormat('H:i', $argument = $line[1]) === false) {
        throw new InvalidArgumentException($argument, 'session start', 'time');
    }
    // Validate session end
    if (\DateTime::createFromFormat('H:i', $argument = $line[2]) === false) {
        throw new InvalidArgumentException($argument, 'session end', 'time');
    }
    // Validate specialism
    if (!is_string($argument = $line[3])) {
        throw new InvalidArgumentException($argument, 'specialism', 'string');
    }
    // Validate staff
    if (!is_string($argument = $line[4])) {
        throw new InvalidArgumentException($argument, 'staff', 'string');
    }
    // Validate agenda
    if (!is_string($argument = $line[5])) {
        throw new InvalidArgumentException($argument, 'agenda', 'string');
    }
    // Validate department
    if (!is_string($argument = $line[6])) {
        throw new InvalidArgumentException($argument, 'department', 'string');
    }
}
```

The importSpecialism, importStaff, importDepartment and importPoliAnalyzeAgenda methods aren't defined in the import type class or in the base import class. These methods are defined in trait classes. I will explain traits later ...

Finally the import of the line gets finalized and the finalizeImport method gets called. This method is defined in the import type class and combines all relationships and handles the main entity (in this example the PoliAnalyzeAppointmentHour entity).

Example of the finalizeImport method:

```php
protected function finalizeImport(
    $date,
    $sessionStart,
    $sessionEnd,
    \Bluetea\CoreBundle\Entity\Specialism $specialism,
    \Bluetea\CoreBundle\Entity\Staff $staff,
    \Bluetea\CoreBundle\Entity\PoliAnalyzeAgenda $agenda,
    \Bluetea\CoreBundle\Entity\Department $department
)
{
    $dateStart = new \DateTime($date . ' ' . $sessionStart);
    $dateEnd = new \DateTime($date . ' ' . $sessionEnd);
    // Create cachingId and is mandatory if you're using the cache!
    $cacheId = "PoliAnalyzeAppointmentHour_cachedOneByReference_" . $agenda->getReference() . $staff->getReference() . $dateStart->format('YmdHi');
    // Try to find the entity in the database
    $appointmentHour = $this->em->getRepository('BlueteaCoreBundle:PoliAnalyzeAppointmentHour')
        ->cachedOneByReference($agenda, $staff, $dateStart, $cacheId);
    // Check if the appointment exists, if not create a new appointment if allowed
    if (is_null($appointmentHour)) {
        $appointmentHour = new PoliAnalyzeAppointmentHour();
    }
    // Create a copy for later comparison
    $originalAppointmentHour = clone $appointmentHour;
    // Update the entity
    $appointmentHour->setStartDate($dateStart);
    $appointmentHour->setEndDate($dateEnd);
    $appointmentHour->setSpecialism($specialism);
    $appointmentHour->setStaff($staff);
    $appointmentHour->setagenda($agenda);
    $appointmentHour->setDepartment($department);

    // persistAndFlush does validation, checks if an entity is changed and handles the cache
    try {
        $this->persistAndFlush($appointmentHour, $originalAppointmentHour, $cacheId, true);
    } catch (\Exception $e) {
        throw new ImportException('Couldn\'t flush the entities: ' . $e->getMessage());
    }
}
```

Thats everything for the import type! It's very easy to add more import types and traits prevent code duplication.

### Traits

In the Import Types section we already mentioned the traits. Traits are special classes which implement methods which are used in many other classes. Traits are actually classes which are extended by the child class (in this example the PoliAnalyzeAppointmentHourImportType).

Example:

```php
class PoliAnalyzeAppointmentHourImportType extends BaseImport implements ImportInterface
{
    use Specialism;
    use Staff;
    use PoliAnalyzeAgenda;
    use Department;
```

More information about traits can be found on the [php.net website](http://www.php.net/manual/en/language.oop5.traits.php).

Caution! Traits are available since PHP 5.4! When using traits it isn't possible to run the application on PHP 5.3 or older.

### Import Services

The ImportBundle comes with a CSVImport service. Exactly, this service has a relationship with the CSVFactory earlier discussed. So it's possible to add more Import Services which have there own factory.

The CSVImport service extends the Services\Import class which contains all the default business logic. Check out the source code for the details!