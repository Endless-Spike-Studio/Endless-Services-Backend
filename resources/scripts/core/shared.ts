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
