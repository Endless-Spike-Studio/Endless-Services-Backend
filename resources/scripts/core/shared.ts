export const defaultLevelDesc = '(No description provided)';

export interface Server {
    name: string;
    address: string;
}

export const servers = [
    {
        name: '官服',
        address: 'http://www.boomlings.com/database'
    }
] as Server[];

export const lengths = {
    0: 'Tiny',
    1: 'Short',
    2: 'Medium',
    3: 'Long',
    4: 'XL'
}

export const tracks = {
    '-1': {
        name: 'Practice: Stay Inside Me',
        artist_name: 'OcularNebula'
    },
    0: {
        name: 'Stereo Madness',
        artist_name: 'Foreverbound'
    },
    1: {
        name: 'Back on Track',
        artist_name: 'DJVI'
    },
    2: {
        name: 'Polargeist',
        artist_name: 'Step'
    },
    3: {
        name: 'Dry Out',
        artist_name: 'DJVI'
    },
    4: {
        name: 'Base after Base',
        artist_name: 'DJVI'
    },
    5: {
        name: 'Cant Let Go',
        artist_name: 'DJVI'
    },
    6: {
        name: 'Jumper',
        artist_name: 'Waterflame'
    },
    7: {
        name: 'Time Machine',
        artist_name: 'Waterflame'
    },
    8: {
        name: 'Cycles',
        artist_name: 'DJVI'
    },
    9: {
        name: 'xStep',
        artist_name: 'DJVI'
    },
    10: {
        name: 'Clutterfunk',
        artist_name: 'Waterflame'
    },
    11: {
        name: 'Theory of Everything',
        artist_name: 'DJ-Nate'
    },
    12: {
        name: 'Electroman Adventures',
        artist_name: 'Waterflame'
    },
    13: {
        name: 'Clubstep',
        artist_name: 'DJ-Nate'
    },
    14: {
        name: 'Electrodynamix',
        artist_name: 'DJ-Nate'
    },
    15: {
        name: 'Hexagon Force',
        artist_name: 'Waterflame'
    },
    16: {
        name: 'Blast Processing',
        artist_name: 'Waterflame'
    },
    17: {
        name: 'Theory of Everything 2',
        artist_name: 'DJ-Nate'
    },
    18: {
        name: 'Geometrical Dominator',
        artist_name: 'Waterflame'
    },
    19: {
        name: 'Deadlocked',
        artist_name: 'F-777'
    },
    20: {
        name: 'Fingerdash',
        artist_name: 'MDK'
    },
    21: {
        name: 'The Seven Seas',
        artist_name: 'F-777'
    },
    22: {
        name: 'Viking Arena',
        artist_name: 'F-777'
    },
    23: {
        name: 'Airborne Robots',
        artist_name: 'F-777'
    },
    24: {
        name: 'The Challenge',
        artist_name: 'RobTop'
    },
    25: {
        name: 'Payload',
        artist_name: 'Dex Arson'
    },
    26: {
        name: 'Beast Mode',
        artist_name: 'Dex Arson'
    },
    27: {
        name: 'Machina',
        artist_name: 'Dex Arson'
    },
    28: {
        name: 'Years',
        artist_name: 'Dex Arson'
    },
    29: {
        name: 'Frontlines',
        artist_name: 'Dex Arson'
    },
    30: {
        name: 'Space Pirates',
        artist_name: 'Waterflame'
    },
    31: {
        name: 'Striker',
        artist_name: 'Waterflame'
    },
    32: {
        name: 'Embers',
        artist_name: 'Dex Arson'
    },
    33: {
        name: 'Round 1',
        artist_name: 'Dex Arson'
    },
    34: {
        name: 'Monster Dance Off',
        artist_name: 'F-777'
    },
    35: {
        name: 'Press Start',
        artist_name: 'MDK'
    },
    36: {
        name: 'Nock Em',
        artist_name: 'Bossfight'
    },
    37: {
        name: 'Power Trip',
        artist_name: 'Boom Kitty'
    }
} as Record<number, {
    name: string;
    artist_name: string;
}>;
