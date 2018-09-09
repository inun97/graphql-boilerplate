<?php
namespace Helper;

use \Types\Types;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;

class MyGraphQL {
  private $reqInput = [];
  private $dbConfig = [];
  
  public function __construct() {
    $this->get_input();
    $this->dbConfig = @parse_ini_file('dbconfig.ini');
  }

  /**
   * Memproses input dan menghasilkan data hasil proses graphql
   *
   * @return array
   */
  public function graphql() {
    $connection = new \Pixie\Connection($this->dbConfig['driver'], $this->dbConfig);
    $qb = new \Pixie\QueryBuilder\QueryBuilderHandler($connection);

    // graphql start
    $schema = new Schema([
      'query' => Types::getType('query'),
      'mutation' => Types::getType('mutation')
    ]);

    try {
      $qb->query("START TRANSACTION");
      $result = GraphQL::executeQuery(
        $schema,
        $this->reqInput['query'],
        null,
        ['qb' => $qb],
        (array) $this->reqInput['variables']
      )->toArray();
      $qb->query("COMMIT");
      return $result;
    } catch(\Exception $e) {
      $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
      return [
        'errors' => [
          FormattedError::createFromException($e, $debug)
        ]
      ];
    }
  }

  /**
   * Mengecek beragam input untuk digunakan oleh graphql
   *
   * @return void
   */
  private function get_input() {
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        // ... ambil data berdasarkan php://input (request body)
        $raw = file_get_contents('php://input') ?: '';
        $this->reqInput = json_decode($raw, true);
    } else {
        // kalau bukan, ambil data berdasarkan $_REQUEST ($_POST dan $_GET)
        $this->reqInput = $_REQUEST;
    }
    // array union (memastikan supaya tidak terjadi error undefined index array)
    $this->reqInput += ['query' => null, 'variables' => null];
    // jika tidak terdapat query pada data ...
    if (null === $this->reqInput['query']) {
        // ... set query menjadi '{hello}' (default query)
        $this->reqInput['query'] = '{hello}';
    }
  }
}