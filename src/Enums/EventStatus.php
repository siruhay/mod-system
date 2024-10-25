<?php

namespace Module\System\Enums;

enum EventStatus: string
{
    case Approved   = 'approved';
    case Confirmed  = 'confirmed';
    case Created    = 'created';
    case Deleted    = 'deleted';
    case Determined = 'determined';
    case Drafted    = 'drafted';
    case Failed     = 'failed';
    case Finalized  = 'finalized';
    case Pending    = 'pending';
    case Posted     = 'posted';
    case Printed    = 'printed';
    case Published  = 'published';
    case Proposed   = 'proposed';
    case Rejected   = 'rejected';
    case Repaired   = 'repaired';
    case Restored   = 'restored';
    case Signed     = 'signed';
    case Submitted  = 'submitted';
    case Synced     = 'synced';
    case Trashed    = 'trashed';
    case Updated    = 'updated';
    case Verified   = 'verified';
}
