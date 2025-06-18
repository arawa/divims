<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BigBlueButton; if not, see <https://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Responses;

use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class GetMeetingsResponseTest extends TestCase
{
    private GetMeetingsResponse $meetings;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('get_meetings.xml');

        $this->meetings = new GetMeetingsResponse($xml);
    }

    public function testGetMeetingsResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->meetings->getReturnCode());

        $this->assertCount(3, $this->meetings->getMeetings());

        $aMeeting = $this->meetings->getMeetings()[2];

        $this->assertEquals('56e1ae16-3dfc-390d-b0d8-5aa844a25874', $aMeeting->getMeetingId());
        $this->assertEquals('Marty Lueilwitz', $aMeeting->getMeetingName());
        $this->assertEquals(1453210075799, $aMeeting->getCreationTime());
        $this->assertEquals('Tue Jan 19 08:27:55 EST 2016', $aMeeting->getCreationDate());
        $this->assertEquals(49518, $aMeeting->getVoiceBridge());
        $this->assertEquals('580.124.3937x93615', $aMeeting->getDialNumber());
        $this->assertEquals('f~kxYJeAV~G?Jb+E:ggn', $aMeeting->getAttendeePassword());
        $this->assertEquals('n:"zWc##Bi.y,d^s,mMF', $aMeeting->getModeratorPassword());
        $this->assertFalse($aMeeting->hasBeenForciblyEnded());
        $this->assertTrue($aMeeting->isRunning());
        $this->assertEquals(5, $aMeeting->getParticipantCount());
        $this->assertEquals(2, $aMeeting->getListenerCount());
        $this->assertEquals(1, $aMeeting->getVoiceParticipantCount());
        $this->assertEquals(3, $aMeeting->getVideoCount());
        $this->assertEquals(2206, $aMeeting->getDuration());
        $this->assertTrue($aMeeting->hasUserJoined());
        $this->assertEquals(14, $aMeeting->getMaxUsers());
        $this->assertEquals(1, $aMeeting->getModeratorCount());
        $this->assertEquals('Consuelo Gleichner IV', $aMeeting->getMetas()['presenter']);
        $this->assertEquals('http://www.muller.biz/autem-dolor-aut-nam-doloribus-molestiae', $aMeeting->getMetas()['endcallbackurl']);
        $this->assertFalse($aMeeting->isBreakout());
    }

    public function testGetMeetingsResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->meetings, ['getReturnCode']);

        $aMeeting = $this->meetings->getMeetings()[2];

        $this->assertEachGetterValueIsString($aMeeting, ['getMeetingId', 'getMeetingName', 'getCreationDate', 'getDialNumber',
            'getAttendeePassword', 'getModeratorPassword', ]);
        $this->assertEachGetterValueIsDouble($aMeeting, ['getCreationTime']);
        $this->assertEachGetterValueIsInteger($aMeeting, ['getVoiceBridge', 'getParticipantCount', 'getListenerCount',
            'getVoiceParticipantCount', 'getVideoCount', 'getDuration', ]);
        $this->assertEachGetterValueIsBoolean($aMeeting, ['hasBeenForciblyEnded', 'isRunning', 'hasUserJoined']);
    }
}
