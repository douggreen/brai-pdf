<?php

namespace App;

// TODO: correct sheet size
//define('LinesPerColumn', round(6.2 * 5.5));

class BRAI
{

  const MaxColumns = 3;

  const MaxCharactersPerLine = 37;

  /**
   * The number of lines per column.
   *
   * @var int
   */
  protected $linesPerColumn;

  /**
   * @param int $height
   */
  public function __construct(int $height)
  {
    $this->linesPerColumn = (int) round($height / 16);
  }

  /**
   * Gets the number of lines per column.
   *
   * @return int
   */
  public function getLinesPerColumn()
  {
    return $this->linesPerColumn;
  }

  // determine the number of rows the meeting text will occupy and add that to the number of lines in the column
  public function getNumMeetingLines(mixed $meeting) : int
  {
    $meeting_location = $meeting->location && $meeting->location !== $meeting->name ? $meeting->location . '|' : '';
    $wrapped_meeting_string = wordwrap($meeting->name . "|" . $meeting_location . $meeting->address . ' (' . implode(', ', $meeting->types) . ")",
      self::MaxCharactersPerLine, "|");
    // adding 2: one for the date - region and one because the count of "|" doesn't account for the last string
    return substr_count($wrapped_meeting_string, "|") + 2;
  }

  public function checkNewRowColumn(int $row, int $column, int $num_column_lines, int $lines_per_column, string $day = ""): array
  {
    if ($this->needNewColumn($num_column_lines, $lines_per_column))
    {
      if ($this->isLastColumn($column))
      {
        echo $this->newRow();
        if ($day) {}
        {
          echo $this->newDay($day);
        }
        $row++;
        $column = 1;
        $num_column_lines = 4;
      }
      else
      {
        echo $this->newColumn();
        $column++;
        $num_column_lines = 0;
      }
    }
    return [$row, $column, $num_column_lines];
  }

  protected function needNewColumn(int $num_column_lines, int $lines_per_column) : bool
  {
    return $num_column_lines >= $lines_per_column;
  }

  protected function isLastColumn(int $column) : bool
  {
    return $column == self::MaxColumns;
  }

  protected function newDay(string $day) : string
  {
    return '<h1 class="brai-day">' . strtoupper($day) . ' MEETINGS</h1>';
  }

  protected function newRow() : string
  {
    return '</div></div><div class="row"><div class="column">';
  }

  protected function newColumn() : string
  {
    return '</div><div class="column">';
  }

}
