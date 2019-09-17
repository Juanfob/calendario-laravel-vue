<?php

namespace App\Http\Controllers;

use App\CalendarEvent;

class CalendarEventController extends Controller
{
	public function eventsForMonth ($month, $year) {
		/*CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 1',
			'hour' => '10:00',
			'day' => 3,
			'month' => 1,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 2',
			'hour' => '12:00',
			'day' => 10,
			'month' => 1,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 3',
			'hour' => '18:30',
			'day' => 19,
			'month' => 1,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 4',
			'hour' => '10:00',
			'day' => 24,
			'month' => 1,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 5',
			'hour' => '11:00',
			'day' => 24,
			'month' => 1,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 6',
			'hour' => '08:00',
			'day' => 15,
			'month' => 2,
			'year' => 2019
		]);
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => 'success',
			'content' => 'Evento 7',
			'hour' => '12:00',
			'day' => 30,
			'month' => 2,
			'year' => 2019
		]);*/
		$events = CalendarEvent::where('month', $month)
	        ->where('year', $year)
	        ->where('user_id', auth()->id())
			->get();
		return response()->json($events);
	}

	public function eventsCountForYear ($year) {
		$events = CalendarEvent::where('year', $year)
			->where('user_id', auth()->id())
			->groupBy('month')
			->selectRaw('count(*) as total, month')
			->get();
		return response()->json($events);
	}

	public function store () {
		CalendarEvent::create([
			'user_id' => auth()->id(),
			'type' => request('type'),
			'content' => request('content'),
			'hour' => request('hour'),
			'day' => request('day'),
			'month' => request('month'),
			'year' => request('year')
		]);
		return response()->json(['res' => 'evento creado correctamente']);
	}
}
