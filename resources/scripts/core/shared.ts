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
    '-1': 'Practice: Stay Inside Me By OcularNebula',
    0: 'Stereo Madness By Foreverbound',
    1: 'Back on Track By DJVI',
    2: 'Polargeist By Step',
    3: 'Dry Out By DJVI',
    4: 'Base after Base By DJVI',
    5: 'Cant Let Go By DJVI',
    6: 'Jumper By Waterflame',
    7: 'Time Machine By Waterflame',
    8: 'Cycles By DJVI',
    9: 'xStep By DJVI',
    10: 'Clutterfunk By Waterflame',
    11: 'Theory of Everything By DJ-Nate',
    12: 'Electroman Adventures By Waterflame',
    13: 'Clubstep By DJ-Nate',
    14: 'Electrodynamix By DJ-Nate',
    15: 'Hexagon Force By Waterflame',
    16: 'Blast Processing By Waterflame',
    17: 'Theory of Everything 2 By DJ-Nate',
    18: 'Geometrical Dominator By Waterflame',
    19: 'Deadlocked By F-777',
    20: 'Fingerdash By MDK',
    21: 'The Seven Seas By F-777',
    22: 'Viking Arena By F-777',
    23: 'Airborne Robots By F-777',
    24: 'The Challenge By RobTop',
    25: 'Payload By Dex Arson',
    26: 'Beast Mode By Dex Arson',
    27: 'Machina By Dex Arson',
    28: 'Years By Dex Arson',
    29: 'Frontlines By Dex Arson',
    30: 'Space Pirates By Waterflame',
    31: 'Striker By Waterflame',
    32: 'Embers By Dex Arson',
    33: 'Round 1 By Dex Arson',
    34: 'Monster Dance Off By F-777',
    35: 'Press Start By MDK',
    36: 'Nock Em By Bossfight',
    37: 'Power Trip By Boom Kitty'
} as Record<number, string>;
